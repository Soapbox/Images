const puppeteer = require('./node_modules/puppeteer/index');
const browserFetcher = puppeteer.createBrowserFetcher({ platform: 'linux' });

const revision = require('./node_modules/puppeteer/package.json').puppeteer.chromium_revision;

const revisionInfo = browserFetcher.revisionInfo(revision);

// Do nothing if the revision is already downloaded.
if (revisionInfo.local) {
    return;
}

browserFetcher.download(revisionInfo.revision, onProgress).then(onSuccess);

function onSuccess(localRevisions) {
    console.log('Chromium downloaded to ' + revisionInfo.folderPath);
}

let progressBar = null;
let lastDownloadedBytes = 0;
function onProgress(downloadedBytes, totalBytes) {
    if (!progressBar) {
        const ProgressBar = require('progress');
        progressBar = new ProgressBar(`Downloading Chromium r${revision} - ${toMegabytes(totalBytes)} [:bar] :percent :etas `, {
            complete: '=',
            incomplete: ' ',
            width: 20,
            total: totalBytes,
        });
    }
    const delta = downloadedBytes - lastDownloadedBytes;
    lastDownloadedBytes = downloadedBytes;
    progressBar.tick(delta);
}

function toMegabytes(bytes) {
    const mb = bytes / 1024 / 1024;
    return `${Math.round(mb * 10) / 10} Mb`;
}
