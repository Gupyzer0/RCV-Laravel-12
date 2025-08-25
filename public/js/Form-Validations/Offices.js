    const formOffice = document.getElementById('offices_form');
    let notValidatedOffices = [1, 2, 3, 4];

    const address = document.getElementById('address');
    const estado = document.getElementById('estado');
    const municipio = document.getElementById('municipio');
    const parroquia = document.getElementById('parroquia');

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

    address.addEventListener('keyup', () => {
        if (address.value === '' || address.value == null) {
            address.classList.add('is-invalid');
            if (!notValidatedOffices.includes(1)) {     
                notValidatedOffices.push(1);
            }
        } else {
            address.classList.remove('is-invalid');         
            address.classList.add('is-valid');
            removeA(notValidatedOffices, 1);
        }     
    });

    estado.addEventListener('change', () => {
        if (estado.value === '' || estado.value == null) {
            estado.classList.add('is-invalid');
            if (!notValidatedOffices.includes(2)) {     
                notValidatedOffices.push(2);
            }
        } else {
            estado.classList.remove('is-invalid');         
            estado.classList.add('is-valid');
            removeA(notValidatedOffices, 2);
        }     
    });

    municipio.addEventListener('change', () => {
        if (municipio.value === '' || municipio.value == null) {
            municipio.classList.add('is-invalid');
            if (!notValidatedOffices.includes(3)) {     
                notValidatedOffices.push(3);
            }
        } else {
            municipio.classList.remove('is-invalid');         
            municipio.classList.add('is-valid');
            removeA(notValidatedOffices, 3);
        }     
    });

    parroquia.addEventListener('change', () => {
        if (parroquia.value === '' || parroquia.value == null) {
            parroquia.classList.add('is-invalid');
            if (!notValidatedOffices.includes(4)) {     
                notValidatedOffices.push(4);
            }
        } else {
            parroquia.classList.remove('is-invalid');         
            parroquia.classList.add('is-valid');
            removeA(notValidatedOffices, 4);
        }         
    });

    formOffice.addEventListener('submit', (e) => {
        if (notValidatedOffices.length > 0) {
            e.preventDefault();
        }
    })