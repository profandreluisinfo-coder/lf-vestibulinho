window.showCourseDetails = function (id, name, description, duration, info, vacancies) {
    document.getElementById('view-name').textContent = name;
    document.getElementById('view-description').textContent = description;
    document.getElementById('view-duration').textContent = duration;
    document.getElementById('view-info').textContent = info;
    document.getElementById('view-vacancies').textContent = vacancies;
}