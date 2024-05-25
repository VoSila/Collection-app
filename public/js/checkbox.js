var checkboxes = document.querySelectorAll('input.thing'),
  checkall = document.getElementById('selectAllCheckbox');

for(var i=0; i<checkboxes.length; i++) {
  checkboxes[i].onclick = function() {
    var checkedCount = document.querySelectorAll('input.thing:checked').length;

    checkall.checked = checkedCount > 0;
    checkall.indeterminate = checkedCount > 0 && checkedCount < checkboxes.length;
  }
}

checkall.onclick = function() {
  for(var i=0; i<checkboxes.length; i++) {
    checkboxes[i].checked = this.checked;
  }
}
