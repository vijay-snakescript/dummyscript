class conditionalScript {
    constructor() {
        // Select all elements that have data-conditional-name attribute
        const elements = document.querySelectorAll('[data-conditional-name]');
        // Loop through elements
        for (const element of elements) {
            // Get names, values and the conditional fields
            const condName = element.dataset.conditionalName;
            const condValue = element.dataset.conditionalValue;
            const condParent = element.parentNode;

            // Select all names
            const names = document.querySelectorAll(`[name="${condName}"]`);
            // Function return true if values is matched
            const valueMatch = v => condValue.includes(v);
            // Shorthand functions show/hide elements
            const hide = e => e.style.display = 'none';
            const show = e => e.style.display = '';
            // Loop through names
            for (const name of names) {
                console.log(name.type)
                // Select, Radio fields have same conditional
                if ("select-one" === name.type || "radio" === name.type) {
                    // Hide the conditional field if the value doesn't match
                    if (!valueMatch(name.value)) {
                        hide(condParent);
                    }
                    // Check on change to show / hide
                    name.addEventListener('change', e => valueMatch(e.target.value) ? show(condParent) : hide(condParent));
                }
                // Checkbox field
                else if ("checkbox" === name.type) {
                    // Hide the conditional field if the value doesn't match and not checked
                    if (!name.checked)
                        hide(condParent);
                    // Check on change
                    name.addEventListener('change', e => e.target.checked ? show(condParent) : hide(condParent));
                }
            }
        }
    }
}
// Run the class!
window.conditional = new conditionalScript();
