const formTypes = document.querySelector('#types_form');
const typeInput = document.querySelector('#type');
let notValidatedTypes = [1];

function removeA(arr) {
    var what, a = arguments, L = a.length, ax;
    while (L > 1 && arr.length) {
        what = a[--L];
        while ((ax= arr.indexOf(what)) !== -1) {
            arr.splice(ax, 1);
        }
    }
    return arr;
}

typeInput.addEventListener('keyup', () => {
	if (typeInput.value === '' || typeInput.value == null) {
		typeInput.classList.add('is-invalid');
		if (!notValidatedTypes.includes(1)) {
			notValidatedTypes.push(1);
		}
	}else {
		typeInput.classList.remove('is-invalid');
		typeInput.classList.add('is-valid');
		removeA(notValidatedTypes, 1);
	}
});

formTypes.addEventListener('submit', (e) => {
	if (notValidatedTypes.length > 0) {
		e.preventDefault();
	}
});