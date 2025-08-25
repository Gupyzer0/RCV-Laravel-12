document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('form_policies');
    const btn = document.getElementById('submitButton');

    // Validadores generales
    const validators = {
        email: value => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value),
        numeric: value => /^\d+$/.test(value),
        ci: value => /^[0-9]{1,10}$/.test(value), // Máximo 10 números
        year: value => /^\d{4}$/.test(value) && value >= 1920 && value <= 2026,
        required: value => value.trim() !== "",
        color: value => /^[a-zA-Z_ ]+$/.test(value), // Solo letras y espacios
        name: value => /^[a-zA-ZÀ-ÿ0-9\s./&-]{1,255}$/.test(value), // Letras, espacios, min 1, max 255
        min: (value, min) => value.length >= min,
        max: (value, max) => value.length <= max,
        regex: (value, pattern) => new RegExp(pattern).test(value),
        date: value => !isNaN(Date.parse(value)), // Validar fechas
    };

    // Lista de campos con sus reglas
    const fields = [
        { id: 'client_name', rules: ['required', 'name', ['max', 255], ['min', 1]] },
        { id: 'client_lastname', rules: ['required', 'name', ['max', 255], ['min', 1]] },
        { id: 'id_type', rules: ['required'] },
        { id: 'ci', rules: ['required', 'ci'] },
        { id: 'number_code', rules: ['required'] },
        { id: 'client_phone', rules: ['required', ['regex', /^[0-9]{1,8}$/]] }, // Min 1, Max 8, solo números
        { id: 'client_email', rules: ['required', 'email', ['max', 255]] },
        { id: 'email_t', rules: ['required', 'email', ['max', 255]] },
        { id: 'estado', rules: ['required'] },
        { id: 'municipio', rules: ['required'] },
        { id: 'parroquia', rules: ['required'] },
        { id: 'client_name_contractor', rules: ['required', 'name', ['max', 255], ['min', 1]] },
        { id: 'client_lastname_contractor', rules: ['required', 'name', ['max', 255], ['min', 1]] },
        { id: 'id_type_contractor', rules: ['required'] },
        { id: 'ci_contractor', rules: ['required', 'ci'] },
        { id: 'brand', rules: ['required', ['max', 255]] },
        { id: 'model', rules: ['required', ['max', 255]] },
        { id: 'vehicle_year', rules: ['required', 'year'] },
        { id: 'vehicle_class', rules: ['required'] },
        { id: 'vehicle_color', rules: ['required', 'color', ['max', 25], ['min', 1]] },
        { id: 'used_for', rules: ['required'] },
        { id: 'vehicle_bodywork_serial', rules: ['required', ['max', 25], ['min', 1]] },
        { id: 'vehicle_motor_serial', rules: ['required', ['max', 25], ['min', 1]] },
        { id: 'vehicle_certificate_number', rules: ['required', ['max', 25], ['min', 1]] },
        { id: 'vehicle_weight', rules: ['required', ['regex', /^[0-9]+$/]] }, // Solo números
        { id: 'vehicle_registration', rules: ['required', ['max', 15], ['min', 1]] },
        { id: 'price', rules: ['required'] },
        { id: 'fecha_n', rules: ['required', 'date'] },
        { id: 'genero', rules: ['required'] },
        { id: 'estadocivil', rules: ['required'] },
        { id: 'client_address', rules: ['required', ['max', 255]] },
    ];

    const notValidated = new Set(fields.map(field => field.id));

    // Validar un campo específico
    function validateField(field) {
        const element = document.getElementById(field.id);
        let isValid = true;

        field.rules.forEach(rule => {
            if (Array.isArray(rule)) {
                const [name, param] = rule;
                if (!validators[name](element.value.trim(), param)) {
                    isValid = false;
                }
            } else {
                if (!validators[rule](element.value.trim())) {
                    isValid = false;
                }
            }
        });

        if (isValid) {
            element.classList.remove('is-invalid');
            element.classList.add('is-valid');
            notValidated.delete(field.id);
        } else {
            element.classList.add('is-invalid');
            element.classList.remove('is-valid');
            notValidated.add(field.id);
        }
    }

    // Agregar validadores a los campos
    fields.forEach(field => {
        const element = document.getElementById(field.id);
        if (element) {
            const eventType = element.tagName === 'SELECT' ? 'change' : 'keyup';
            element.addEventListener(eventType, () => validateField(field));
        }
    });

    // Validar el formulario al enviar
    form.addEventListener('submit', e => {
        fields.forEach(validateField);
        if (notValidated.size > 0) {
            e.preventDefault();
        }
    });

});
