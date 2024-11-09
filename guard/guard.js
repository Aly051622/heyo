document.addEventListener("DOMContentLoaded", function () {
    // Retrieve the selected area from localStorage, or default to 'Front Admin' if not set
    var savedArea = localStorage.getItem('selectedArea') || 'Front Admin';
    
    // Display the saved area's slots on page load
    filterByArea(savedArea);

    // Set event listeners for area buttons
    document.getElementById('btnFrontAdmin').addEventListener('click', function () {
        filterByArea('Front Admin');
    });
    document.getElementById('btnBesideCME').addEventListener('click', function () {
        filterByArea('Beside CME');
    });
    document.getElementById('btnKadasig').addEventListener('click', function () {
        filterByArea('Kadasig');
    });
    document.getElementById('btnBehind').addEventListener('click', function () {
        filterByArea('Behind');
    });

    // Event listener for slot number input to capitalize letters
    document.getElementById('slotNumberInput').addEventListener('input', function () {
        this.value = this.value.toUpperCase(); // Capitalize the input
    });

    // Event listener for form submission to validate slot number
    document.querySelector('form').addEventListener('submit', function (e) {
        var area = document.getElementById('areaSelect').value;
        var slotNumber = document.getElementById('slotNumberInput').value;

        // Determine the prefix based on the selected area
        var expectedPrefix = getAreaPrefix(area);

        // Validate that the slot number starts with the correct prefix
        if (!slotNumber.startsWith(expectedPrefix) || !/^\w\d+$/.test(slotNumber)) {
            alert('Invalid slot number! Slot number should start with ' + expectedPrefix + ' and be followed by numbers (e.g., ' + expectedPrefix + '1).');
            e.preventDefault(); // Prevent form submission
        }
    });

    // Add event listener for mobile menu toggle
    document.querySelector('.navbar-toggler').addEventListener('click', function () {
        toggleMenu();
    });

    // Add event listeners for dropdowns
    var dropdowns = document.querySelectorAll('.dropdown');
    dropdowns.forEach(function (dropdown) {
        dropdown.addEventListener('click', function () {
            toggleDropdown(this);
        });
    });
});

// Search for a slot by slot number
function searchSlot() {
    var input = document.getElementById('searchInput').value.toLowerCase();
    var slots = document.getElementsByClassName('slot');

    if (input.length === 1 && isNaN(input)) {
        // User entered a letter, show all slots that match the area prefix
        for (var i = 0; i < slots.length; i++) {
            var slot = slots[i];
            if (slot.textContent.toLowerCase().startsWith(input)) {
                slot.style.display = '';
            } else {
                slot.style.display = 'none';
            }
        }
    } else if (input.length > 1) {
        // User entered a slot number like A1
        for (var i = 0; i < slots.length; i++) {
            var slot = slots[i];
            if (slot.textContent.toLowerCase().includes(input)) {
                slot.style.display = '';
            } else {
                slot.style.display = 'none';
            }
        }
    } else if (!isNaN(input)) {
        alert('Please include the area, e.g., A1');
    }
}

// Handle updating slot status dynamically
function updateSlotStatus(slotNumber, status) {
    var formData = new FormData();
    formData.append('slotNumber', slotNumber);
    formData.append('status', status);
    formData.append('action', 'updateStatus');

    fetch('monitor.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(result => {
        alert('Slot updated: ' + result);

        // Update the status dynamically without reloading the page
        var slot = document.querySelector(`div[data-slot-number="${slotNumber}"]`);
        slot.dataset.status = status; // Update the data attribute
        if (status === 'Vacant') {
            slot.classList.remove('occupied');
            slot.classList.add('vacant');
        } else {
            slot.classList.remove('vacant');
            slot.classList.add('occupied');
        }
    });
}

// Handle deleting a slot dynamically
function deleteSlot(slotNumber) {
    var formData = new FormData();
    formData.append('slotNumber', slotNumber);
    formData.append('action', 'deleteSlot');

    fetch('monitor.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(result => {
        alert('Slot deleted: ' + result);

        // Remove the slot from the DOM dynamically without reloading the page
        var slot = document.querySelector(`div[data-slot-number="${slotNumber}"]`);
        if (slot) {
            slot.remove();
        }
    });
}

// Filter slots by area and save the selected area to localStorage
function filterByArea(area) {
    // Map area names to corresponding class names for filtering
    var areaClasses = {
        'Front Admin': 'front-admin',
        'Beside CME': 'beside-cme',
        'Kadasig': 'kadasig',
        'Behind': 'behind'
    };

    // Save the selected area to localStorage
    localStorage.setItem('selectedArea', area);

    // Get all slots
    var allSlots = document.getElementsByClassName('slot');

    // Loop through all slots and show/hide based on the selected area
    for (var i = 0; i < allSlots.length; i++) {
        var slot = allSlots[i];
        slot.style.display = 'none'; // Hide all slots initially

        // Show slots that match the selected area's class
        if (slot.classList.contains(areaClasses[area])) {
            slot.style.display = 'block';
        }
    }
}

// Toggle the display of buttons for the clicked slot
function toggleSlotButtons(slotNumber) {
    // Hide all other slot actions first
    var allSlots = document.getElementsByClassName('slot-actions');
    for (var i = 0; i < allSlots.length; i++) {
        allSlots[i].style.display = 'none';
    }

    // Now show the actions for the clicked slot
    var slotActions = document.getElementById('slotActions-' + slotNumber);
    if (slotActions.style.display === 'none' || slotActions.style.display === '') {
        slotActions.style.display = 'block';
    } else {
        slotActions.style.display = 'none';
    }
}

// Helper function to get the prefix based on the selected area
function getAreaPrefix(area) {
    switch (area) {
        case 'Front Admin':
            return 'A';
        case 'Beside CME':
            return 'B';
        case 'Kadasig':
            return 'C';
        case 'Behind':
            return 'D';
        default:
            return '';
    }
}

// Toggle the navigation menu for mobile view
function toggleMenu() {
    var menu = document.querySelector('.navbar-menu');
    menu.classList.toggle('active'); // Toggle the 'active' class to show/hide the menu
}

// Toggle the dropdown menu content
function toggleDropdown(dropdownElement) {
    var dropdownContent = dropdownElement.querySelector('.dropdown-content');
    dropdownContent.classList.toggle('show'); // Toggle the visibility of the dropdown content
}

var selectedSlots = [];
let allSlotsSelected = false; 

// Mark selected slots as vacant or occupied
function markSlotAs(status) {
    // Batch process all selected slots at once
    selectedSlots.forEach(slot => {
        updateSlotStatus(slot, status === 'vacant' ? 'Vacant' : 'Occupied');
    });

    // Notify for multiple slots at once
    if (selectedSlots.length > 0) {
        alert('Slots ' + selectedSlots.join(', ') + ' will be ' + status);
    }

    // Clear selected slots after marking
    selectedSlots = [];
    hideToggleMenu(); // Hide toggle-btn after marking
}

// Select individual slot
function selectSlot() {
    document.querySelectorAll('.slot').forEach(slot => {
        slot.addEventListener('click', function () {
            // If the slot is not yet selected, select it
            if (!selectedSlots.includes(slot.dataset.slotNumber)) {
                selectedSlots.push(slot.dataset.slotNumber);
                slot.style.border = '4px solid blue'; // Highlight selected slot
                showToggleMenu();
            } else {
                // If the slot is already selected, deselect it
                selectedSlots.splice(selectedSlots.indexOf(slot.dataset.slotNumber), 1);
                slot.style.border = ''; // Remove highlight
            }

            // Check if all slots are selected
            const totalSlots = document.querySelectorAll('.slot').length;
            if (selectedSlots.length === totalSlots) {
                allSlotsSelected = true;
            } else {
                allSlotsSelected = false;
            }

            // Hide toggle-btn if no slots are selected
            if (selectedSlots.length === 0) {
                hideToggleMenu();
            }
        });
    });
}

// Select or deselect all slots
function selectAll() {
    const slots = document.querySelectorAll('.slot');

    if (allSlotsSelected) {
        // If all slots are selected, deselect them
        selectedSlots = [];
        slots.forEach(slot => {
            slot.style.border = ''; // Remove highlight
        });
        hideToggleMenu(); // Hide the toggle-btn
        allSlotsSelected = false;
    } else {
        // If not all slots are selected, select them
        selectedSlots = [];
        slots.forEach(slot => {
            slot.style.border = '4px solid blue'; // Highlight slots
            selectedSlots.push(slot.dataset.slotNumber);
        });
        showToggleMenu(); // Show the toggle-btn
        allSlotsSelected = true;
    }
}

// Delete selected slots
function deleteSelected() {
    if (confirm('Are you sure you want to delete the selected slots?')) {
        // Batch delete all selected slots at once
        selectedSlots.forEach(slotNumber => {
            deleteSlot(slotNumber);
        });

        // Notify for multiple slots at once
        if (selectedSlots.length > 0) {
            alert('Slots ' + selectedSlots.join(', ') + ' have been deleted.');
        }

        // Clear selected slots after deletion
        selectedSlots = [];
        hideToggleMenu(); // Hide toggle-btn after deletion
    }
}

// Show the toggle-btn when slots are selected
function showToggleMenu() {
    const toggleBtn = document.getElementById('toggle-btn');
    if (toggleBtn) {
        toggleBtn.style.display = 'block';  // Show the toggle-btn
    }
}

// Hide the toggle-btn when no slots are selected
function hideToggleMenu() {
    const toggleBtn = document.getElementById('toggle-btn');
    if (toggleBtn) {
        toggleBtn.style.display = 'none';  // Hide the toggle-btn
    }
}

// Get the sidebar and toggle button elements
const sidebar = document.getElementById('sidebar');
const toggleMenuBtn = document.getElementById('toggleMenuBtn');

// Add click event listener to toggle button
toggleMenuBtn.addEventListener('click', function() {
    // Toggle the sidebar open/close by adding/removing the 'active' class
    sidebar.classList.toggle('active');
});
