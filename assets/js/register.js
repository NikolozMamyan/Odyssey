const form = document.querySelector('form[name="registration_form"]');
const etudiant = form.elements["registration_form[etudiant]"]
const formateur = form.elements["registration_form[formateur]"]

form.addEventListener('input', (e) => {

    const formFirstName = form.elements["registration_form[firstNameUser]"];
    const formLastName = form.elements["registration_form[lastNameUser]"];
    const formEmail = form.elements["registration_form[email]"];
    const formPasswordFirst = form.elements["registration_form[plainPassword][first]"];
    const formPasswordSecond = form.elements["registration_form[plainPassword][second]"];

    if (e.target === formFirstName) {

        if (formFirstName.value.length <= 2) {
            formFirstName.classList.add('sk-redBorder');
        } else {
            formFirstName.classList.remove('sk-redBorder');
        }
    }

    if (e.target === formLastName) {

        if (formLastName.value.length <= 2) {
            formLastName.classList.add('sk-redBorder');
        } else {
            formLastName.classList.remove('sk-redBorder');
        }
    }

    if (e.target === formEmail) {

        const emailRegex = /^[a-z0-9.-]+@[a-z0-9.-]+(\.[a-z]{2,4})$/;

        if (!emailRegex.test(formEmail.value)) {
            formEmail.classList.add('sk-redBorder');
        } else {
            formEmail.classList.remove('sk-redBorder');
        }
    }

    if (e.target === formPasswordFirst){

        const regexPwd = /(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])[a-zA-Z0-9]{8,12}/;

        if (!regexPwd.test(formPasswordFirst.value)) {
            formPasswordFirst.classList.add('sk-redBorder');
        }else{
            formPasswordFirst.classList.remove('sk-redBorder')
        }
    }

    if (e.target === formPasswordSecond){

        if (formPasswordFirst.value != formPasswordSecond.value) {
            formPasswordSecond.classList.add('sk-redBorder');
        }else{
            formPasswordSecond.classList.remove('sk-redBorder')
        }
    }

});


etudiant.addEventListener('change', function() {
    if (this.checked) {
        formateur.checked = false;
    }
});

formateur.addEventListener('change', function() {
    if (this.checked) {
        etudiant.checked = false;
    }
});
