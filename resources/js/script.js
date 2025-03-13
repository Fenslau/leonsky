
document.addEventListener('DOMContentLoaded', function () {
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));

    const containers = document.querySelectorAll('.card-body');

    containers.forEach(container => {
        const textBlock = container.querySelector('.card-text');

        if (textBlock) {
            const containerHeight = container.clientHeight;
            const textBlockHeight = textBlock.scrollHeight;

            if (textBlockHeight > containerHeight) {
                textBlock.classList.add('text-overflow-container');
            }
        }
    });
});

Echo.channel('server-status')
    .listen('.ServerStatusUpdated', (e) => {
        if (e.message === 'login on') {
            updateStatus('login-status', 'text-bg-success', 'Online');
        } else if (e.message === 'login off') {
            updateStatus('login-status', 'text-bg-danger', 'Offline');
        } else if (e.message === 'game on') {
            updateStatus('game-status', 'text-bg-success', 'Online');
        } else if (e.message === 'game off') {
            updateStatus('game-status', 'text-bg-danger', 'Offline');
        }
    });

function updateStatus(elementId, badgeClass, text) {
    const element = document.getElementById(elementId);
    if (element) {
        element.className = `d-inline-block badge w-auto p-1 mb-1 ${badgeClass}`;
        //element.textContent = text;
    }
}