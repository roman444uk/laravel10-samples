import './bootstrap';
import './ziggy';
import {createApp, createVNode, render} from 'vue';
import * as app from './../assets/js/app.js'
import * as appAwsS3 from './../assets/js/app-aws-s3.js'
import * as appFunctions from './../assets/js/app-functions.js'
import * as appFunctionsFileUpload from './../assets/js/app-functions-file-upload.js'
import * as appCommon from './../assets/js/app-common.js'

const vueApp = createApp({});

window.vueApp = vueApp;
window.vueApp.config.performance = true;
window.vueApp.config.devtools = true;
window.vueApp.config.productionTip = false;

window.postMessage({
    devtoolsEnabled: true,
    vueDetected: true
}, '*')

const globalScope = {
    install(app, options) {
        app.config.globalProperties.route = window.route;
        app.config.globalProperties.trans = trans;
        app.config.globalProperties.getCsrfToken = getCsrfToken
    }
}

/**
 * Multiple vue instances on page
 */
const components = {};

Object.entries({
    ...import.meta.glob('./Components/*.vue', {eager: true}),
    ...import.meta.glob('./Components/WebViewer/*.vue', {eager: true})
}).forEach(([path, definition]) => {
    const componentName = path.split('/')
        .pop()
        .replace(/\.\w+$/, '')
        .replace(/[A-Z]/g, letter => `-${letter.toLowerCase()}`)
        .substring(1);

    components[componentName] = definition;
});

Object.entries(components).forEach((entry) => {
    const [componentName] = entry;

    Array.from(document.getElementsByTagName(componentName)).forEach((element, index) => {
        element.id = componentName + '-' + index;

        const vueApp = createApp({
            template: element.outerHTML
        });

        /**
         * Inject global functions
         */
        vueApp.use(globalScope, {

        });

        Object.entries(components).forEach((entry) => {
            const [componentName, definition] = entry;
            vueApp.component(componentName, definition.default);
        });

        document.addEventListener('DOMContentLoaded', () => {
            vueApp.mount('#' + element.id);
        });
    });
});
