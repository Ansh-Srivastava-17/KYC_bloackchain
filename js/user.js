document.addEventListener('DOMContentLoaded', () => {
    const uploadForm = document.getElementById('uploadForm');
    uploadForm.addEventListener('submit', handleUpload);
});

function handleUpload(e) {
    e.preventDefault();
    const formData = new FormData(e.target);

    fetch('../php/upload_documents.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.text();
    })
    .then(text => {
        try {
            return JSON.parse(text);
        } catch (e) {
            console.error('Server response:', text);
            throw new Error('The server response was not valid JSON');
        }
    })
    .then(data => {
        if (data.success) {
            alert('Documents uploaded successfully');
            location.reload();
        } else {
            alert('Error uploading documents: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while uploading documents. Please check the console for more information.');
    });
}