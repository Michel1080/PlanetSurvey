document.getElementById('add_activity').addEventListener('click', function () {
    let activities = document.getElementById('activities');
    let activityCount = activities.getElementsByClassName('activity').length;

    let newActivity = document.createElement('div');
    newActivity.className = 'activity';

    newActivity.innerHTML = `
        <label for="activity_name_${activityCount + 1}">Activity Name:</label>
        <input type="text" id="activity_name_${activityCount + 1}" name="activities[${activityCount}][name]" required>
        <label for="location_${activityCount + 1}">Location:</label>
        <input type="text" id="location_${activityCount + 1}" name="activities[${activityCount}][location]" required>
        <label for="assigned_person_${activityCount + 1}">Assigned Person:</label>
        <input type="text" id="assigned_person_${activityCount + 1}" name="activities[${activityCount}][assigned_person]" required>
        <label for="date_${activityCount + 1}">Date:</label>
        <input type="date" id="date_${activityCount + 1}" name="activities[${activityCount}][date]" required>
        <label for="budget_${activityCount + 1}">Budget:</label>
        <input type="number" step="0.01" id="budget_${activityCount + 1}" name="activities[${activityCount}][budget]" required>
    `;

    activities.appendChild(newActivity);
});
