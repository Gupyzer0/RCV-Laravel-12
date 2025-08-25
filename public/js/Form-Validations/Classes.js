const formClass = document.getElementById('classes_form');
const classInput = document.getElementById('class');

let notValidatedClass = [1];

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

classInput.addEventListener('keyup', () => {
	if (classInput.value === '' || classInput.value == null) {
		classInput.classList.add('is-invalid');
		if (!notValidatedClass.includes(1)) {
			notValidatedClass.push(1);
		}
	}else {
		classInput.classList.remove('is-invalid');
		classInput.classList.add('is-valid');
		removeA(notValidatedClass, 1);
	}
});

formClass.addEventListener('submit', (e) => {
	if (notValidatedClass.length > 0) {
		e.preventDefault();
	}
});