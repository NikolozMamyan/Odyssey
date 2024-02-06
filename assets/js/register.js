const form = document.querySelector('form[name="registration_form"]');

form.addEventListener('input', () => {

    const formFirstName = form.elements["registration_form[firstNameUser]"];
    const formLastName = form.elements["registration_form[lastNameUser]"];
    const formEmail = form.elements["registration_form[email]"].value;
    const formPasswordFirst = form.elements["registration_form[plainPassword][first]"];
    const formPasswordSecond = form.elements["registration_form[plainPassword][second]"];
    const formPasswordLabel = document.querySelector('label[for="registration_form_plainPassword_second"]');
    const formTerms = form.elements["registration_form[agreeTerms]"].value;

    if(formFirstName.value.length <= 2) {
        //todo: ajouter classe bordure rouge
        formFirstName.classList.add('redBorder');
    } else {
        formFirstName.classList.remove('redBorder');
    }

    if(formLastName.value.length <= 2) {
        //todo: ajouter classe bordure rouge
        formLastName.classList.add('redBorder');
    } else {
        formLastName.classList.remove('redBorder');
    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if(!emailRegex.test(formEmail)) {
        //todo: ajouter classe bordure rouge
        //formEmail.classList.add('redBorder');
    } else {
        //formEmail.classList.remove('redBorder');
    }

    if(!formPasswordFirst.value) {
        //todo: ajouter classe bordure rouge
        //formPasswordFirst.classList.add('redBorder');
    } else {
        //formPasswordFirst.classList.remove('redBorder');
        formPasswordFirst.addEventListener('input', function() {
            formPasswordLabel.classList.add('d-block');
            formPasswordLabel.classList.remove('d-none');
            formPasswordSecond.classList.add('d-block');
            formPasswordSecond.classList.remove('d-none');
        });


        //formPasswordFirst.classList.remove('redBorder');
    }

    if(!formTerms.checked) {
        //todo: ajouter classe bordure rouge
        //formTerms.classList.add('redBorder');
    } else {
        //formTerms.classList.remove('redBorder');
    }



});

