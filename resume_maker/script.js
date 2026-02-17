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

function renderResumeFromData(data, template) {
    if (!data) {
        resumeDiv.innerHTML = '<p style="text-align:center;">No resume data found.</p>';
        return;
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
                <h3>Experience</h3>
                <p>${data.experience}</p>
            </div>
        `;
    } else {
        // default/template1
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
}

if (resumeDiv) {
    const urlParams = new URLSearchParams(window.location.search);
    const resumeId = urlParams.get('id');
    const template = localStorage.getItem("selectedTemplate") || "template1";

    if (resumeId) {
        // fetch resume from server by id
        fetch(`get_resume.php?id=${encodeURIComponent(resumeId)}`)
            .then(res => {
                if (!res.ok) throw new Error('Resume not found');
                return res.json();
            })
            .then(data => {
                renderResumeFromData(data, template);
            })
            .catch(err => {
                resumeDiv.innerHTML = `<p style="text-align:center;color:#c33;">${err.message}</p>`;
            });
    } else {
        const data = JSON.parse(localStorage.getItem("resumeData") || 'null');
        renderResumeFromData(data, template);
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
