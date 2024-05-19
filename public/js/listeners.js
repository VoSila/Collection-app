document.addEventListener('DOMContentLoaded', function () {
  $('#block_button').on('click', function () {
    let checkboxes = $('input[type="checkbox"]:checked');
    let selectedCollectionIds = [];

    checkboxes.each(function (index) {
      selectedCollectionIds.push($(checkboxes[index]).data('collectionId'));
      if (selectedCollectionIds[0] == null) {
        selectedCollectionIds = selectedCollectionIds.slice(1)
      }
    });
    console.log(selectedCollectionIds)

    if (selectedCollectionIds.length > 1) {
      console.log('Выберите только одну коллекцию для редактирования');
      return;
    }
    let url = '/collections/' + selectedCollectionIds + '/update';

    window.location.href = url;
  });
});
//
//   // Click handler for check all checkboxes
//   $('#selectAllCheckbox').on('click', function () {
//     let state = $('#selectAllCheckbox').prop('checked');
//     changeCheckboxesState(state);
//   });
// });
//
// function changeCheckboxesState(state) {
//   $('#selectAllCheckbox').prop('checked', state);
//
//   let checkboxes = $('input[type="checkbox"][data-user-id]:not(#selectAllCheckbox)');
//   checkboxes.each(function (index) {
//     $(checkboxes[index]).prop('checked', state);
//   });
// }
//
// function resetCheckboxesState() {
//   $('#selectAllCheckbox').prop('checked', false);
//
//   $('input[type="checkbox"][data-user-id]:not(#selectAllCheckbox)').prop('checked', false);
// }


// function sendDataToServer(path) {
//   console.log(path)
//   $.ajax({
//     url: path,
//     type: 'POST',
//     contentType: 'application/json',
//     beforeSend: function () {
//       $('.loader').show();
//     },
//     success: function (data) {
//       $('.loader').hide();
//
//     },
//     error: function () {
//       $('.loader').hide();
//       console.log('Ajax request failed.');
//     },
//     complete: function (xhr, status) {
//       if (xhr.status === 403) {
//         window.location.reload();
//       }
//       console.log('Ajax request completed with status: ' + status);
//     }
//   });
// }
