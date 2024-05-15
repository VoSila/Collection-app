function updateTableWithData(users) {
  var tableBody = $('table tbody');
  tableBody.empty();

  users.forEach(function (user) {
    var newRow = $('<tr>' +
      '<td><input class="PrivateSwitchBase-input css-1m9pwf3" tabindex="-1" type="checkbox" ' +
      'id="checkbox_' + user.id + '" name="user_checkbox" ' +
      'data-indeterminate="false" aria-label="Select all rows" ' +
      'data-user-id="' + user.id + '"></td>' +
      '<td>' + user.id + '</td>' +
      '<td>' + user.name + '</td>' +
      '<td>' + user.email + '</td>');

    if (user.status === 1) {
      newRow.append('<td>Active</td>');
    } else if (user.status === 2) {
      newRow.append('<td>Block</td>');
    }

    newRow.append('<td>' + user.dateLastLogin + '</td>' +
      '<td>' + user.dateRegister + '</td>' +
      '</tr>');

    tableBody.append(newRow);
  });
  resetCheckboxesState();
}
