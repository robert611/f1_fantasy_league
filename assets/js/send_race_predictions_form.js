document.getElementById('race-predictions-form-trigger').addEventListener('click', (event) => {
    event.preventDefault();
    
    let form = document.getElementById('race-predictions-form');

    form.submit();
});