function initSectionChooser() {
    let sectionChooser = document.getElementById('sectionChooser');

    if (!sectionChooser) {
        return;
    }

    sectionChooser.addEventListener('change', function() {
        var selection = this.value;

        document.querySelectorAll('.panel').forEach(function(panel) {
            panel.style.display = 'none';
        });

        if (selection) {
            var targetPanel = document.getElementById(selection);

            if (targetPanel) {
                targetPanel.style.display = 'block';
            }
        }
    });
}

function doctorsSelectAll() {
    let selectAllDoctorsCheckbox = document.querySelector('.doctors-select-all');
    let doctorCheckboxes = document.querySelectorAll('.delete_select');

    if (!selectAllDoctorsCheckbox) {
        return;
    }

    selectAllDoctorsCheckbox.addEventListener('change', function() {
        doctorCheckboxes.forEach((checkBox) => {
            checkBox.checked = this.checked;
        });
    });
}

function loadFile(event) {
    let output = document.getElementById('output');

    if (!output || !event.target.files || !event.target.files[0]) {
        return;
    }

    output.src = URL.createObjectURL(event.target.files[0]);
    output.onload = function() {
        URL.revokeObjectURL(output.src); // free memory
    };
}

function fileLoadDoctor() {
    let fileInput = document.querySelector('input[type="file"][name="photo"]');

    if (!fileInput) {
        return;
    }

    fileInput.addEventListener('change', loadFile);
}
function getCheckedDoctorIds() {
    let doctorsCheckBoxes = document.querySelectorAll('.delete_select:checked');
    let doctorIds = Array.from(doctorsCheckBoxes).map(checkbox => checkbox.value);
    
    if (doctorIds.length > 0) {
        new bootstrap.Modal(document.getElementById('delete_select')).show();
        document.getElementById('delete_select_id').value = doctorIds.join(',');
    } else {
        alert('يرجى اختيار طبيب واحد على الأقل للحذف.');
    }

    return doctorIds;
}

document.addEventListener('DOMContentLoaded', function() {
    doctorsSelectAll();
    initSectionChooser();
    fileLoadDoctor();
    getCheckedDoctorIds();
});
