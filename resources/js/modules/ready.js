"use strict";

// syntax: ES5

var $ = function (callbackDocumentObj) {
    var ready = function (callback) {
        var docObj = callbackDocumentObj;
        // distinguish between $(function(){}) and $(document).ready(function(){})
        if (typeof docObj !== "object") docObj = document;
        if (docObj.readyState !== "loading") {
            callback();
        } else if (
            docObj.addEventListener !== null &&
            docObj.addEventListener !== undefined
        ) {
            docObj.addEventListener("DOMContentLoaded", callback, false);
        } else {
            docObj.attachEvent("onreadystatechange", function () {
                if (docObj.readyState === "complete") {
                    callback();
                }
            });
        }
    };
    if (typeof callbackDocumentObj === "object") {
        return {
            ready: ready,
        };
    } else {
        return ready(callbackDocumentObj);
    }
};

// export function
window.$ = $;
