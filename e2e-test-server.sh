# Make sure to set GOOGLE_CHROME env variable pointing to your Google Chrome binary. For example on macOS:
# GOOGLE_CHROME="/Applications/Google Chrome.app/Contents/MacOS/Google Chrome" ./e2e-test-server.sh

trap 'kill %1; kill %2' SIGINT
cd tests/Application && APP_ENV=test symfony server:start --port=8080 --dir=public & "${GOOGLE_CHROME}" --enable-automation --disable-background-networking --no-default-browser-check --no-first-run --disable-popup-blocking --disable-default-apps --allow-insecure-localhost --disable-translate --disable-extensions --no-sandbox --enable-features=Metal --headless --remote-debugging-port=9222 --disable-gpu --window-size=2880,1800 --proxy-server='direct://' --proxy-bypass-list='*' --crash-dumps-dir=/tmp https://127.0.0.1 & tee
