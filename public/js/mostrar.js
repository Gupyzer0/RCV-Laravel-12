
document.addEventListener('DOMContentLoaded', () => {
    // Seleccionar los tres input y el div oculto
    const campo = document.getElementById('campo');
    const campoc = document.getElementById('campoc');
    const campop = document.getElementById('campop');
    const divOculto = document.getElementById('divOculto');

    // Función para mostrar el div oculto cuando el último input se complete
    function mostrarDivOculto() {
        if (campo.value !== '' && campoc.value !== '' && campop.value !== '') {
            divOculto.style.display = ''; // quitar el display'
        } else {
            divOculto.style.display = 'none';
        }
    }

    // Escuchar los eventos de cambio en los input y llamar a la función mostrarDivOculto
    campo.addEventListener('input', mostrarDivOculto);
    campoc.addEventListener('input', mostrarDivOculto);
    campop.addEventListener('input', mostrarDivOculto);

    //Segundo DIV

    const campo1 = document.getElementById('campo1');
    const campoc1 = document.getElementById('campoc1');
    const campop1 = document.getElementById('campop1');
    const divOculto2 = document.getElementById('divOculto2');

    // Función para mostrar el div oculto cuando el último input se complete
    function mostrarDivOculto2() {
        if (campo1.value !== '' && campoc1.value !== '' && campop1.value !== '') {
            divOculto2.style.display = ''; // quitar el display'
        } else {
            divOculto2.style.display = 'none';
        }
    }

    // Escuchar los eventos de cambio en los input y llamar a la función mostrarDivOculto
    campo1.addEventListener('input', mostrarDivOculto2);
    campoc1.addEventListener('input', mostrarDivOculto2);
    campop1.addEventListener('input', mostrarDivOculto2);

    //TERCERO DIV

    const campo2 = document.getElementById('campo2');
    const campoc2 = document.getElementById('campoc2');
    const campop2 = document.getElementById('campop2');
    const divOculto3 = document.getElementById('divOculto3');

    // Función para mostrar el div oculto cuando el último input se complete
    function mostrarDivOculto3() {
        if (campo2.value !== '' && campoc2.value !== '' && campop2.value !== '') {
            divOculto3.style.display = ''; // quitar el display'
        } else {
            divOculto3.style.display = 'none';
        }
    }

    // Escuchar los eventos de cambio en los input y llamar a la función mostrarDivOculto
    campo2.addEventListener('input', mostrarDivOculto3);
    campoc2.addEventListener('input', mostrarDivOculto3);
    campop2.addEventListener('input', mostrarDivOculto3);

    //CUARTO DIV

    const campo3 = document.getElementById('campo3');
    const campoc3 = document.getElementById('campoc3');
    const campop3 = document.getElementById('campop3');
    const divOculto4 = document.getElementById('divOculto4');
    // Función para mostrar el div oculto cuando el último input se complete
    function mostrarDivOculto4() {
        if (campo3.value !== '' && campoc3.value !== '' && campop3.value !== '') {
            divOculto4.style.display = ''; // quitar el display'
        } else {
            divOculto4.style.display = 'none';
        }
    }
    // Escuchar los eventos de cambio en los input y llamar a la función mostrarDivOculto
    campo3.addEventListener('input', mostrarDivOculto4);
    campoc3.addEventListener('input', mostrarDivOculto4);
    campop3.addEventListener('input', mostrarDivOculto4);

    //QUINTO DIV

    const campo4 = document.getElementById('campo4');
    const campoc4 = document.getElementById('campoc4');
    const campop4 = document.getElementById('campop4');
    const divOculto5 = document.getElementById('divOculto5');

    // Función para mostrar el div oculto cuando el último input se complete
    function mostrarDivOculto5() {
        if (campo4.value !== '' && campoc4.value !== '' && campop4.value !== '') {
            divOculto5.style.display = ''; // quitar el display'
        } else {
            divOculto5.style.display = 'none';
        }
    }

    // Escuchar los eventos de cambio en los input y llamar a la función mostrarDivOculto
    campo4.addEventListener('input', mostrarDivOculto5);
    campoc4.addEventListener('input', mostrarDivOculto5);
    campop4.addEventListener('input', mostrarDivOculto5);

    //SEXTO DIV

    const campo5 = document.getElementById('campo5');
    const campoc5 = document.getElementById('campoc5');
    const campop5 = document.getElementById('campop5');
    const divOculto6 = document.getElementById('divOculto6');

    // Función para mostrar el div oculto cuando el último input se complete
    function mostrarDivOculto6() {
        if (campo5.value !== '' && campoc5.value !== '' && campop5.value !== '') {
            divOculto6.style.display = ''; // quitar el display'
        } else {
            divOculto6.style.display = 'none';
        }
    }

    // Escuchar los eventos de cambio en los input y llamar a la función mostrarDivOculto
    campo5.addEventListener('input', mostrarDivOculto6);
    campoc5.addEventListener('input', mostrarDivOculto6);
    campop5.addEventListener('input', mostrarDivOculto6);

});

//decimales
document.addEventListener('DOMContentLoaded', () => {
    let campoc = SimpleMaskMoney.setMask('#campoc', {
        prefix: '',
        suffix: '',
        fixed: true,
        fractionDigits: 2,
        decimalSeparator: '.',
        thousandsSeparator: '',
        emptyOrInvalid: () => {
            return this.SimpleMaskMoney.args.fixed
            ? `0${this.SimpleMaskMoney.args.decimalSeparator}00`
            : `_${this.SimpleMaskMoney.args.decimalSeparator}__`;
        }
    });

    let campop = SimpleMaskMoney.setMask('#campop', {
        prefix: '',
        suffix: '',
        fixed: true,
        fractionDigits: 2,
        decimalSeparator: '.',
        thousandsSeparator: '',
        emptyOrInvalid: () => {
            return this.SimpleMaskMoney.args.fixed
            ? `0${this.SimpleMaskMoney.args.decimalSeparator}00`
            : `_${this.SimpleMaskMoney.args.decimalSeparator}__`;
        }
    });

    let campoc1 = SimpleMaskMoney.setMask('#campoc1', {
        prefix: '',
        suffix: '',
        fixed: true,
        fractionDigits: 2,
        decimalSeparator: '.',
        thousandsSeparator: '',
        emptyOrInvalid: () => {
            return this.SimpleMaskMoney.args.fixed
            ? `0${this.SimpleMaskMoney.args.decimalSeparator}00`
            : `_${this.SimpleMaskMoney.args.decimalSeparator}__`;
        }
    });

    let campop1 = SimpleMaskMoney.setMask('#campop1', {
        prefix: '',
        suffix: '',
        fixed: true,
        fractionDigits: 2,
        decimalSeparator: '.',
        thousandsSeparator: '',
        emptyOrInvalid: () => {
            return this.SimpleMaskMoney.args.fixed
            ? `0${this.SimpleMaskMoney.args.decimalSeparator}00`
            : `_${this.SimpleMaskMoney.args.decimalSeparator}__`;
        }
    });

    let campoc2 = SimpleMaskMoney.setMask('#campoc2', {
        prefix: '',
        suffix: '',
        fixed: true,
        fractionDigits: 2,
        decimalSeparator: '.',
        thousandsSeparator: '',
        emptyOrInvalid: () => {
            return this.SimpleMaskMoney.args.fixed
            ? `0${this.SimpleMaskMoney.args.decimalSeparator}00`
            : `_${this.SimpleMaskMoney.args.decimalSeparator}__`;
        }
    });

    let campop2 = SimpleMaskMoney.setMask('#campop2', {
        prefix: '',
        suffix: '',
        fixed: true,
        fractionDigits: 2,
        decimalSeparator: '.',
        thousandsSeparator: '',
        emptyOrInvalid: () => {
            return this.SimpleMaskMoney.args.fixed
            ? `0${this.SimpleMaskMoney.args.decimalSeparator}00`
            : `_${this.SimpleMaskMoney.args.decimalSeparator}__`;
        }
    });

    let campoc3 = SimpleMaskMoney.setMask('#campoc3', {
        prefix: '',
        suffix: '',
        fixed: true,
        fractionDigits: 2,
        decimalSeparator: '.',
        thousandsSeparator: '',
        emptyOrInvalid: () => {
            return this.SimpleMaskMoney.args.fixed
            ? `0${this.SimpleMaskMoney.args.decimalSeparator}00`
            : `_${this.SimpleMaskMoney.args.decimalSeparator}__`;
        }
    });

    let campop3 = SimpleMaskMoney.setMask('#campop3', {
        prefix: '',
        suffix: '',
        fixed: true,
        fractionDigits: 2,
        decimalSeparator: '.',
        thousandsSeparator: '',
        emptyOrInvalid: () => {
            return this.SimpleMaskMoney.args.fixed
            ? `0${this.SimpleMaskMoney.args.decimalSeparator}00`
            : `_${this.SimpleMaskMoney.args.decimalSeparator}__`;
        }
    });

    let campoc4 = SimpleMaskMoney.setMask('#campoc4', {
        prefix: '',
        suffix: '',
        fixed: true,
        fractionDigits: 2,
        decimalSeparator: '.',
        thousandsSeparator: '',
        emptyOrInvalid: () => {
            return this.SimpleMaskMoney.args.fixed
            ? `0${this.SimpleMaskMoney.args.decimalSeparator}00`
            : `_${this.SimpleMaskMoney.args.decimalSeparator}__`;
        }
    });

    let campop4 = SimpleMaskMoney.setMask('#campop4', {
        prefix: '',
        suffix: '',
        fixed: true,
        fractionDigits: 2,
        decimalSeparator: '.',
        thousandsSeparator: '',
        emptyOrInvalid: () => {
            return this.SimpleMaskMoney.args.fixed
            ? `0${this.SimpleMaskMoney.args.decimalSeparator}00`
            : `_${this.SimpleMaskMoney.args.decimalSeparator}__`;
        }
    });

    let campoc5 = SimpleMaskMoney.setMask('#campoc5', {
        prefix: '',
        suffix: '',
        fixed: true,
        fractionDigits: 2,
        decimalSeparator: '.',
        thousandsSeparator: '',
        emptyOrInvalid: () => {
            return this.SimpleMaskMoney.args.fixed
            ? `0${this.SimpleMaskMoney.args.decimalSeparator}00`
            : `_${this.SimpleMaskMoney.args.decimalSeparator}__`;
        }
    });

    let campop5 = SimpleMaskMoney.setMask('#campop5', {
        prefix: '',
        suffix: '',
        fixed: true,
        fractionDigits: 2,
        decimalSeparator: '.',
        thousandsSeparator: '',
        emptyOrInvalid: () => {
            return this.SimpleMaskMoney.args.fixed
            ? `0${this.SimpleMaskMoney.args.decimalSeparator}00`
            : `_${this.SimpleMaskMoney.args.decimalSeparator}__`;
        }
    });

    let campoc6 = SimpleMaskMoney.setMask('#campoc6', {
        prefix: '',
        suffix: '',
        fixed: true,
        fractionDigits: 2,
        decimalSeparator: '.',
        thousandsSeparator: '',
        emptyOrInvalid: () => {
            return this.SimpleMaskMoney.args.fixed
            ? `0${this.SimpleMaskMoney.args.decimalSeparator}00`
            : `_${this.SimpleMaskMoney.args.decimalSeparator}__`;
        }
    });

    let campop6 = SimpleMaskMoney.setMask('#campop6', {
        prefix: '',
        suffix: '',
        fixed: true,
        fractionDigits: 2,
        decimalSeparator: '.',
        thousandsSeparator: '',
        emptyOrInvalid: () => {
            return this.SimpleMaskMoney.args.fixed
            ? `0${this.SimpleMaskMoney.args.decimalSeparator}00`
            : `_${this.SimpleMaskMoney.args.decimalSeparator}__`;
        }
    });
});
