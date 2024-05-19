function addCollectionAttribute() {
  const collectionHolder = document.querySelector('#custom-attributes-wrapper');
  const item = document.createElement('div');
  item.className = 'item form-control';

  item.innerHTML = collectionHolder
    .dataset
    .prototype
    .replace(
      /__name__/g,
      collectionHolder.dataset.index
    );

  collectionHolder.appendChild(item);

  collectionHolder.dataset.index++;

  addRemoveAttributeButton(item);
}

function addRemoveAttributeButton(item) {
  const removeFormButton = document.createElement('button');
  removeFormButton.type = 'button';
  removeFormButton.classList.add('btn', 'btn-outline-danger');
  removeFormButton.innerHTML = `
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-x-circle me-1" viewBox="0 0 16 16">
            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"></path>
            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"></path>
        </svg>
        Delete Attribute
    `;
  removeFormButton.style.marginBottom = '5px'; // Пример изменения отступа справа

  item.append(removeFormButton);

  removeFormButton.addEventListener('click', (e) => {
    e.preventDefault();
    item.remove();
  });
}

document.addEventListener('DOMContentLoaded', () =>{
  document
    .querySelector('#add-custom-attribute')
    .addEventListener('click', (e) =>{
        e.preventDefault();

        addCollectionAttribute();
    })

  document
    .querySelectorAll('#custom-attributes-wraper div.item')
    .forEach((row)=>{
      addRemoveAttributeButton(row);
    })
})
