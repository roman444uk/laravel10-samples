import {S3, S3Client} from '@aws-sdk/client-s3';
import {XhrHttpHandler} from '@aws-sdk/xhr-http-handler';
import {Upload} from '@aws-sdk/lib-storage';

/**
 * Common
 */
const config = {
    credentials: {
        accessKeyId: import.meta.env.AWS_ACCESS_KEY_ID,
        secretAccessKey: import.meta.env.AWS_SECRET_ACCESS_KEY
    },
    endpoint: import.meta.env.AWS_ENDPOINT,
    s3ForcePathStyle: true,
    region: import.meta.env.AWS_DEFAULT_REGION,
    apiVersion: 'latest'
};

async function awsS3Upload(options) {
    const upload = new Upload({
        client: awsS3ClientGet(),
        params: {
            Bucket: import.meta.env.AWS_BUCKET,
            Key: options.key,
            Body: options.body,
        }
    });

    upload.on('httpUploadProgress', (progress) => {
        let progressPercentage = Math.round(progress.loaded / progress.total * 100);
        // console.log(progressPercentage);

        options.onProgress && options.onProgress(progress, progressPercentage);
    });

    let result = await upload.done();

    options.onSuccess && options.onSuccess(result);
}

window.awsS3Upload = awsS3Upload;

async function awsS3UploadFile(file, options) {
    let reader = new FileReader();
    reader.readAsText(file, 'UTF-8');

    // File reading
    // https://www.google.com/search?q=javascript+get+file+contents&oq=javascript+get+file+&gs_lcrp=EgZjaHJvbWUqBwgGEAAYgAQyBggAEEUYOTIHCAEQABiABDIHCAIQABiABDIHCAMQABiABDIHCAQQABiABDIHCAUQABiABDIHCAYQABiABDIHCAcQABiABDIHCAgQABiABDIHCAkQABiABNIBCTEwMDUzajBqN6gCALACAA&sourceid=chrome&ie=UTF-8
    // https://stackoverflow.com/questions/750032/reading-file-contents-on-the-client-side-in-javascript-in-various-browsers

    reader.onload = (event) => {
        awsS3Upload({
            ...options, ...{
                body: event.target.result
            }
        });
    };
}
window.awsS3UploadFile = awsS3UploadFile;

/**
 * S3
 */
window.awsS3ServiceGet = () => {
    return new S3(config);
}

window.awsS3ClientGet = () => {
    return new S3Client({
        ...config, ...{
            requestHandler: new XhrHttpHandler({})
        }
    });;
}

window.awsS3ObjectPut = (params) => {
    awsS3ServiceGet().putObject({
        ...params, ...{
            Bucket: import.meta.env.AWS_BUCKET,
            Key: params.key,
            Body: params.body,
            ACL: 'public-read'
        }
    }, (err, data) => {
        if (err) {
            console.log('awsS3ObjectPut error');
            console.log(err, err.stack);
        } else {
            console.log('awsS3ObjectPut success');
            console.log(data);
        }
    });
}

window.awsS3ObjectHead = (params) => {
    awsS3ServiceGet().headObject({
        ...params, ...{
            Bucket: import.meta.env.AWS_BUCKET,
            Key: params.key
        }
    }, (err, data) => {
        if (err) {
            console.log('awsS3ObjectHead error');
            console.log(err, err.stack);
        } else {
            console.log('awsS3ObjectHead success');
            console.log(data);
        }
    });
}

window.awsS3ObjectGet = (params) => {
    awsS3ServiceGet().getObject({
        ...params, ...{
            Bucket: import.meta.env.AWS_BUCKET,
            Key: params.key
        }
    }, (err, data) => {
        if (err) {
            console.log('awsS3ObjectGet error');
            console.log(err, err.stack);
        } else {
            console.log('awsS3ObjectGet success');
            console.log(data);
        }
    });
}

window.awsS3ObjectDelete = (params) => {
    awsS3ServiceGet().deleteObject({
        ...params, ...{
            Bucket: import.meta.env.AWS_BUCKET,
            Key: params.key
        }
    }, (err, data) => {
        if (err) {
            console.log('awsS3ObjectDelete error');
            console.log(err, err.stack);
        } else {
            console.log('awsS3ObjectDelete success');
            console.log(data);
        }
    });
}
