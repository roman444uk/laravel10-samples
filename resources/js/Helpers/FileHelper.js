export class FileHelper {
    static getExtensionByName(fileName) {
        return fileName.substring(fileName.lastIndexOf('.') + 1);
    }

    static getIcon(file) {
        return this.getIconByExtension(file.ext);
    }

    static getIconByName(fileName) {
        return this.getIconByExtension(this.getExtensionByName(fileName))
    }

    static getIconByExtension(ext) {
        let icon = 'chat-icon-07.svg';

        if (['jpeg', 'jpg', 'png'].indexOf(ext) > -1) {
            icon = 'gallery-icon.svg';
        }

        if (['doc', 'pdf', 'txt', 'xml', 'xsl'].indexOf(ext) > -1) {
            icon = 'document-icon.svg';
        }

        return '/assets/img/icons/' + icon;
    }

    static getSizeHuman(bytes, si = false, dp = 1) {
        const thresh = si ? 1000 : 1024;

        if (Math.abs(bytes) < thresh) {
            return bytes + ' B';
        }

        // const units = si
        //     ? ['KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB']
        //     : ['KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB'];
        const units = si = ['KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
        let u = -1;
        const r = 10 ** dp;

        do {
            bytes /= thresh;
            ++u;
        } while (Math.round(Math.abs(bytes) * r) / r >= thresh && u < units.length - 1);


        return bytes.toFixed(dp) + ' ' + units[u];
    }

    static isImage(file) {
        return ['gif', 'jpg', 'jpeg', 'png'].indexOf(file.ext) >= 0
    }

    static validateMimeType(mimeType, acceptedMimeTypes) {
        return acceptedMimeTypes.indexOf(mimeType) >= 0 || acceptedMimeTypes.indexOf(getMimeTypeComplex(mimeType));
    }

    static getComplexMimeType = (mimeType) => {
        return mimeType.split('/')[0];
    }
}
