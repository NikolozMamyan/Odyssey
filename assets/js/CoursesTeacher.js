const coursesBtn = document.getElementById('CoursesBtn');

console.log(coursesBtn)

coursesBtn.addEventListener('click', (e) => {
    e.preventDefault();

    coursesBtn.innerHTML += `
    <div>s</div>
    `
})