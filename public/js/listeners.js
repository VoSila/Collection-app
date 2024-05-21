document.addEventListener('DOMContentLoaded', function () {
  document.getElementById('block_button_delete').addEventListener('click', function () {
    var checkboxes = document.querySelectorAll('input[type="checkbox"]:checked');
    var selectedCollectionIds = [];

    checkboxes.forEach(function (checkbox) {
      selectedCollectionIds.push(checkbox.dataset.collectionId);
    });

    if (selectedCollectionIds.length === 0) {
      alert('Please select at least one collection to delete.');
      return;
    }
    var collectionId = document.getElementById('collectionId').value;
    console.log(collectionId);
    if (confirm('Are you sure you want to delete selected collections?')) {
      selectedCollectionIds.forEach(function (collectionId) {
        var form = document.createElement('form');
        form.method = 'POST';
        form.action = '/collections/' + collectionId + '/delete'; // Update the action URL

        var csrfTokenInput = document.createElement('input');
        csrfTokenInput.type = 'hidden';
        csrfTokenInput.name = '_token';
        csrfTokenInput.value =  '{{ csrf_token(delete ~ ) }}';

        form.appendChild(csrfTokenInput);

        document.body.appendChild(form);
        form.submit();
      });
    }
  });
});
