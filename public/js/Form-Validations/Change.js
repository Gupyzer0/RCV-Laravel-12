	const formPass = document.getElementById('CPF');
	const p = document.getElementById('password');
	const pc = document.getElementById('password-confirm');
	let notValidatedPass = [1, 2];

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

	p.addEventListener('keyup', () => {
		if (p.value === "" || p.value == null || p.value.length < 8) {
			p.classList.add('is-invalid');
			if (!notValidatedPass.includes(1)) {     
				notValidatedPass.push(1);
			}
		} else {
			p.classList.remove('is-invalid');         
			p.classList.add('is-valid');
			removeA(notValidatedPass, 1);
		}               
	})

	pc.addEventListener('keyup', () => {
		if (pc.value === "" || pc.value == null || pc.value !== p.value || pc.value.length < 8) {
			pc.classList.add('is-invalid');
			if (!notValidatedPass.includes(2)) {     
				notValidatedPass.push(2);
			}
		} else {
			pc.classList.remove('is-invalid');         
			pc.classList.add('is-valid');
			removeA(notValidatedPass, 2);
		}               
	})

	formPass.addEventListener('submit', (e) => {
		if (notValidatedPass.length > 0) {
			e.preventDefault();
		}
	})