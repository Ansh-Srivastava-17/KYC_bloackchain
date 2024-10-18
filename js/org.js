function approveDoc(docId) {
    updateDocStatus(docId, 'approved');
}

function rejectDoc(docId) {
    updateDocStatus(docId, 'rejected');
}

function updateDocStatus(docId, status) {
    fetch('../php/approve_reject_doc.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `doc_id=${docId}&status=${status}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(`Document ${status} successfully`);
            location.reload();
        } else {
            alert('Error updating document status');
        }
    });
}
