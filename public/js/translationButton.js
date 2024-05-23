// document.addEventListener('DOMContentLoaded', function () {
//   const savedLocale = localStorage.getItem('locale');
//   const languageSwitch = document.getElementById('languageSwitch');
//
//   if (savedLocale === 'ru') {
//     languageSwitch.checked = true;
//   } else {
//     languageSwitch.checked = false;
//   }
//
//   languageSwitch.addEventListener('change', function () {
//     if (this.checked) {
//       localStorage.setItem('locale', 'ru');
//       window.location.href = '{{ path('change_locale', { 'locale': 'ru' }) }}';
//     } else {
//       localStorage.setItem('locale', 'en');
//       window.location.href = '{{ path('change_locale', { 'locale': 'en' }) }}';
//     }
//   });
// });
