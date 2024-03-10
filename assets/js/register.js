if (!window.form) {

    const form = document.querySelector('form[name="registration_form"]');
    const btn = document.querySelector('.sk-btn');
    const etudiant = form.elements["registration_form[student]"]
    const formateur = form.elements["registration_form[teacher]"]
    const firstNameErrorMessage = document.querySelector('#firstNameErrorMessage');
    const lastNameErrorMessage = document.querySelector('#lastNameErrorMessage');
    const emailErrorMessage = document.querySelector('#emailErrorMessage');
    const firstPasswordErrorMessage = document.querySelector('#firstPasswordErrorMessage');
    const secondPasswordErrorMessage = document.querySelector('#secondPasswordErrorMessage');

    let errors = true;

    // Initialisation de l'état du bouton au chargement de la page
    updateButtonState();


    form.addEventListener('input', (e) => {

        const formFirstName = form.elements["registration_form[firstNameUser]"];
        const formLastName = form.elements["registration_form[lastNameUser]"];
        const formEmail = form.elements["registration_form[email]"];
        const formPasswordFirst = form.elements["registration_form[plainPassword][first]"];
        const formPasswordSecond = form.elements["registration_form[plainPassword][second]"];


        // Vérification du champ firstName
        if (e.target === formFirstName) {

            if (formFirstName.value.length <= 2) {
                formFirstName.classList.add('sk-redBorder');
                firstNameErrorMessage.textContent = 'Votre prénom doit contenir au moins 3 caractères.';
            } else {
                formFirstName.classList.remove('sk-redBorder');
                firstNameErrorMessage.textContent = '';

            }
        }

        // Vérification du champ lastName
        if (e.target === formLastName) {

            if (formLastName.value.length <= 2) {
                formLastName.classList.add('sk-redBorder');
                lastNameErrorMessage.textContent = 'Votre nom doit contenir au moins 3 caractères';
            } else {
                formLastName.classList.remove('sk-redBorder');
                lastNameErrorMessage.textContent = '';
            }
        }

        // Vérification du champ email
        if (e.target === formEmail) {

            const emailRegex = /^[a-z0-9.-]+@[a-z0-9.-]+(\.[a-z]{2,4})$/;

            if (!emailRegex.test(formEmail.value)) {
                formEmail.classList.add('sk-redBorder');
                emailErrorMessage.textContent = 'Votre email doit avoir un format valide';
            } else {
                formEmail.classList.remove('sk-redBorder');
                emailErrorMessage.textContent = '';
            }
        }

        // Vérification du champ password
        if (e.target === formPasswordFirst) {

            const regexPwd = /(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])[a-zA-Z0-9]{8,12}/;

            if (!regexPwd.test(formPasswordFirst.value)) {
                formPasswordFirst.classList.add('sk-redBorder');
                firstPasswordErrorMessage.textContent = 'Doit contenir au moins 8 caractères, dont au moins 1 lettre majuscule\n' +
                    'et 1 chiffre';
            } else {
                formPasswordFirst.classList.remove('sk-redBorder');
                firstPasswordErrorMessage.textContent = '';
            }
        }

        if (e.target === formPasswordSecond) {

            if (formPasswordFirst.value !== formPasswordSecond.value) {
                formPasswordSecond.classList.add('sk-redBorder');
                secondPasswordErrorMessage.textContent = 'Vos mot de passe doit être identiques';
            } else {
                formPasswordSecond.classList.remove('sk-redBorder');
                secondPasswordErrorMessage.textContent = '';
            }
        }

        // Vérifie si tous les champs sont remplis
        const allFieldsFilled = form.elements["registration_form[firstNameUser]"].value.trim() !== '' &&
            form.elements["registration_form[lastNameUser]"].value.trim() !== '' &&
            form.elements["registration_form[email]"].value.trim() !== '' &&
            form.elements["registration_form[plainPassword][first]"].value.trim() !== '' &&
            form.elements["registration_form[plainPassword][second]"].value.trim() !== '' &&
            (etudiant.checked || formateur.checked);

        // Met à jour errors
        errors = (firstNameErrorMessage.textContent ||
            lastNameErrorMessage.textContent ||
            emailErrorMessage.textContent ||
            firstPasswordErrorMessage.textContent ||
            secondPasswordErrorMessage.textContent) !== '' ||
            !allFieldsFilled;

        // Mets à jour l'état du bouton
        updateButtonState();


    });

    etudiant.addEventListener('change', function () {
        if (this.checked) {
            formateur.checked = false;
            updateButtonState();

        }
    });

    formateur.addEventListener('change', function () {
        if (this.checked) {
            etudiant.checked = false;
            updateButtonState();

        }
    });


    // Fonction pour mettre à jour l'état du bouton
    function updateButtonState() {
        if (errors) {
            btn.classList.remove('sk-btn');
            btn.classList.add('btn', 'btn-secondary');
            btn.disabled = true;
        } else {
            btn.classList.remove('btn', 'btn-secondary');
            btn.classList.add('sk-btn');
            btn.disabled = false;
        }
    }

}
