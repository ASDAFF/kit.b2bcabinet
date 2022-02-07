$(document).ready(function () {
    let modalManager = document.getElementById("modal_manager");
    let inputs = modalManager.querySelectorAll(".form-group.row");

    for (let i = 0; i < inputs.length; i++) {
        if (inputs[i].querySelector(".form-required.starrequired")) {
            let input = inputs[i].querySelector("input[type='text']");

            input.addEventListener("input", function () {

                if (requiredAllField(inputs)) {
                    disableSentButton(false);
                } else {
                    disableSentButton(true);
                }

                function disableSentButton(disabled) {
                    let button = modalManager.querySelector("[type='submit']");
                    button.disabled = disabled;
                }

            })
        }
    }

    function requiredAllField(inputs) {
        for (let i = 0; i < inputs.length; i++) {
            if (inputs[i].querySelector(".form-required.starrequired")) {
                let input = inputs[i].querySelector("input[type='text']");
                if (input.value.length === 0) {
                    return false;
                }
            }
        }
        return true;
    }
});