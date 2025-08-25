document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('register_form');
    const btn = document.getElementById('submitButton');

    // Validadores generales
    const validators = {
        email: value => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value),
        numeric: value => /^\d+$/.test(value),
        ci: value => /^\d{7,9}$/.test(value),
        year: value => value >= 1920 && value <= 2026,
        porcen: value => {
            const numericValue = parseFloat(value);
            return !isNaN(numericValue) && numericValue >= 20 && numericValue <= 25;
        },
        required: value => value !== "" && value != null,
        color: value => !/^\d+$/.test(value),
        name: value => /^[a-zA-ZÀ-ÿ\s0-9.-]+$/.test(value) // Actualizado para aceptar números, puntos y guiones
    };

    // Mensajes de error personalizados
    const errorMessages = {
        required: 'Este campo es obligatorio.',
        porcen: 'El porcentaje debe estar entre 20% y 25%.',
        email: 'Ingrese un correo electrónico válido.',
        ci: 'La cédula debe tener entre 7 y 9 dígitos.',
        name: 'Este campo solo puede contener letras, números, espacios, puntos y guiones.',
        // Agrega más mensajes según sea necesario
    };

    // Lista de campos con sus reglas
    const fields = [
        { id: 'name', rules: ['required', 'name'] },
        { id: 'porcen', rules: ['required', 'porcen'] },
        { id: 'lastname', rules: ['required', 'name'] },
        { id: 'id_type', rules: ['required'] },
        { id: 'ci', rules: ['required', 'ci'] },
        { id: 'number_code', rules: ['required'] },
        { id: 'phone_number', rules: ['required', 'ci'] },
        { id: 'username', rules: ['required'] },
        { id: 'email', rules: ['required', 'email'] },
        { id: 'office', rules: ['required'] },        
        { id: 'ncontra', rules: ['required'] },
        { id: 'password', rules: ['required'] },
        { id: 'password_confirm', rules: ['required', 'name'] },
    ];

    const notValidated = new Set(fields.map(field => field.id));

    // Validar un campo específico
    function validateField(field) {
        const element = document.getElementById(field.id);
        const value = element.value.trim();

        let isValid = true;
        let errorMessage = '';

        for (const rule of field.rules) {
            if (!validators[rule](value)) {
                isValid = false;
                errorMessage = errorMessages[rule];
                break;
            }
        }

        if (isValid) {
            element.classList.remove('is-invalid');
            element.classList.add('is-valid');
            notValidated.delete(field.id);
        } else {
            element.classList.remove('is-valid');
            element.classList.add('is-invalid');
            element.nextElementSibling.innerHTML = `<strong>${errorMessage}</strong>`;
            notValidated.add(field.id);
        }
    }

    // Validar todos los campos al enviar el formulario
    form.addEventListener('submit', (event) => {
        event.preventDefault();
        fields.forEach(field => validateField(field));

        if (notValidated.size === 0) {
            form.submit();
        }
    });

    // Validar campo individual al cambiar su valor
    fields.forEach(field => {
        const element = document.getElementById(field.id);
        element.addEventListener('input', () => validateField(field));
    });
});
