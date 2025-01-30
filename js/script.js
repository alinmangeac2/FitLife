document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("activity-form");
    const activityList = document.getElementById("activity-list");
    const filterInput = document.getElementById("filter");
    const progressChartCanvas = document.getElementById("progressChart");

    let activities = JSON.parse(localStorage.getItem("activities")) || [];

    function renderActivities(filter = "") {
        activityList.innerHTML = "";
        const filteredActivities = activities.filter(activity => 
            activity.type.toLowerCase().includes(filter.toLowerCase())
        );

        filteredActivities.forEach((activity, index) => {
            const li = document.createElement("li");
            li.innerHTML = `
                <strong>Type:</strong> ${activity.type} <br>
                <strong>Duration:</strong> ${activity.duration} mins <br>
                <strong>Calories:</strong> ${activity.calories} kcal <br>
                <strong>Date:</strong> ${activity.date}
                <button class="delete-btn" data-index="${index}">❌</button>
            `;
            activityList.appendChild(li);
        });

        document.querySelectorAll(".delete-btn").forEach(button => {
            button.addEventListener("click", (e) => {
                const index = e.target.dataset.index;
                activities.splice(index, 1);
                localStorage.setItem("activities", JSON.stringify(activities));
                renderActivities(filterInput.value);
                updateChart();
            });
        });
    }

    form.addEventListener("submit", (e) => {
        e.preventDefault();
        const type = document.getElementById("type").value;
        const duration = document.getElementById("duration").value;
        const calories = document.getElementById("calories").value;
        const date = document.getElementById("date").value;

        if (!type || !duration || !calories || !date) return;

        activities.push({ type, duration, calories, date });
        localStorage.setItem("activities", JSON.stringify(activities));
        renderActivities(filterInput.value);
        updateChart();
        form.reset();
    });

    filterInput.addEventListener("input", () => {
        renderActivities(filterInput.value);
    });

    function updateChart() {
        const ctx = progressChartCanvas.getContext("2d");
        const dates = activities.map(a => a.date).slice(-7);
        const calories = activities.map(a => a.calories).slice(-7);

        if (window.progressChart) {
            window.progressChart.destroy();
        }

        window.progressChart = new Chart(ctx, {
            type: "line",
            data: {
                labels: dates,
                datasets: [{
                    label: "Calories Burned",
                    data: calories,
                    borderColor: "#6200ea",
                    backgroundColor: "rgba(98, 0, 234, 0.2)",
                    fill: true
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    }

    renderActivities();
    updateChart();
});
