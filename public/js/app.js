(function () {
    "use strict";

    var AUTO_DISMISS_MS = 4500;
    var LEAVE_ANIMATION_MS = 250;

    // Notifications (rendered server-side from the session; this only handles closing)
    function dismiss(node) {
        if (!node || node.dataset.closing === "true") return;
        node.dataset.closing = "true";
        node.classList.add("is-leaving");
        window.setTimeout(function () {
            if (node.parentNode) node.parentNode.removeChild(node);
        }, LEAVE_ANIMATION_MS);
    }

    document.querySelectorAll(".notification").forEach(function (note) {
        var close = note.querySelector(".notification-close");
        if (close)
            close.addEventListener("click", function () {
                dismiss(note);
            });
        if (!note.hasAttribute("data-sticky")) {
            window.setTimeout(function () {
                dismiss(note);
            }, note.dataset.duration || AUTO_DISMISS_MS);
        }
    });

    // <form data-confirm="Are you sure?">
    document.querySelectorAll("form[data-confirm]").forEach(function (form) {
        form.addEventListener("submit", function (event) {
            if (!window.confirm(form.dataset.confirm)) event.preventDefault();
        });
    });

    // Inputs that save when changed: <input data-autosubmit>
    document.querySelectorAll("[data-autosubmit]").forEach(function (input) {
        input.addEventListener("change", function () {
            if (input.form) input.form.requestSubmit();
        });
    });

    // Preset cards: swap between the view buttons and the editor
    document.querySelectorAll(".preset-card").forEach(function (card) {
        var view = card.querySelector(".preset-view");
        var editor = card.querySelector(".preset-editor");
        var edit = card.querySelector(".preset-edit");
        var cancel = card.querySelector(".preset-cancel");
        if (!view || !editor || !edit || !cancel) return;

        edit.addEventListener("click", function () {
            view.hidden = true;
            editor.hidden = false;
            var input = editor.querySelector(".preset-input");
            if (input) input.focus();
        });
        cancel.addEventListener("click", function () {
            editor.hidden = true;
            view.hidden = false;
        });
    });

    // Cleaning form: fixed height is only editable when "maximum" is off
    var useMax = document.getElementById("cleaning-use-max");
    var height = document.getElementById("cleaning-height");
    if (useMax && height) {
        var sync = function () {
            height.disabled = useMax.checked;
        };
        useMax.addEventListener("change", sync);
        sync();
    }
})();
