// Selecting element with class "profile"
let profile = document.querySelector('.header .flex .profile');

// Adding a "click" event to "user-btn"
document.querySelector('#user-btn').onclick = () =>{
	profile.classList.toggle('active');
}

// Adding an "onscroll" event
window.onscroll = () =>{
	profile.classList.remove('active');
}

// Adding an "oninput" event to all input elements of type "number"
document.querySelectorAll('input[type="number"]').forEach(inputNumber =>{
	inputNumber.oninput =() =>{
		if (inputNumber.value.length > inputNumber.maxLength) inputNumber.value = inputNumber.value.slice(0, inputNumber.maxLength)
	}
})