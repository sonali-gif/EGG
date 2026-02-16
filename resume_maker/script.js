/* ===== TEMPLATE SELECTION ===== */
function selectTemplate(templateName) {
    localStorage.setItem("selectedTemplate", templateName);
    window.location.href = "form.html";
}

/* ===== FORM SUBMIT ===== */
const form = document.getElementById("resumeForm");

if (form) {
    form.addEventListener("submit", function (e) {
        e.preventDefault();

        const resumeData = {
            name: document.getElementById("name").value,
            email: document.getElementById("email").value,
            phone: document.getElementById("phone").value,
            skills: document.getElementById("skills").value,
            experience: document.getElementById("experience").value
        };

        // Still save locally (optional but good)
        localStorage.setItem("resumeData", JSON.stringify(resumeData));

        // Send to PHP using fetch
        fetch("save.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(resumeData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                window.location.href = "preview.html";
            } else {
                alert("Error saving to database!");
            }
        })
        .catch(error => {
            console.error("Error:", error);
        });
    });
}


/* ===== RESUME PREVIEW ===== */
const resumeDiv = document.getElementById("resume");

if (resumeDiv) {
    const template = localStorage.getItem("selectedTemplate");
    const data = JSON.parse(localStorage.getItem("resumeData"));

    if (template === "template1") {
        resumeDiv.innerHTML = `
            <h2>${data.name}</h2>
            <p>${data.email} | ${data.phone}</p>
            <hr>
            <h3>Skills</h3>
            <p>${data.skills}</p>
            <h3>Experience</h3>
            <p>${data.experience}</p>
        `;
    }

    if (template === "template2") {
        resumeDiv.innerHTML = `
            <div style="border:2px solid black;padding:20px">
                <h1>${data.name}</h1>
                <p>${data.email}</p>
                <p>${data.phone}</p>
                <hr>
                <strong>Skills:</strong>
                <p>${data.skills}</p>
            </div>
        `;
    }
}

function sendSuggestion(text) {
    displayMessage(text, 'user');

    const botResponse = getBotResponse(text);

    setTimeout(() => {
        displayMessage(botResponse, 'bot');
    }, 300);
}


/* ===== DOWNLOAD PDF ===== */
function downloadPDF() {
    const element = document.getElementById("resume");
    html2pdf().from(element).save("My_Resume.pdf");
}
