function approveOrg(orgId) {
    updateOrgStatus(orgId, 'approved');
}

function rejectOrg(orgId) {
    updateOrgStatus(orgId, 'rejected');
}

function updateOrgStatus(orgId, status) {
    fetch('../php/approve_reject_org.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `org_id=${orgId}&status=${status}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(`Organization ${status} successfully`);
            location.reload();
        } else {
            alert('Error updating organization status');
        }
    });
}
