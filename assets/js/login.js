console.clear();

const loginBtn = document.getElementById('login');
const signupBtn = document.getElementById('signup');

loginBtn.addEventListener('click', (e) => {
	let parent = e.target.parentNode.parentNode;
	Array.from(e.target.parentNode.parentNode.classList).find((element) => {
		if(element !== "slide-up") {
			parent.classList.add('slide-up')
		}else{
			signupBtn.parentNode.classList.add('slide-up')
			parent.classList.remove('slide-up')
		}
	});
});

signupBtn.addEventListener('click', (e) => {
	let parent = e.target.parentNode;
	Array.from(e.target.parentNode.classList).find((element) => {
		if(element !== "slide-up") {
			parent.classList.add('slide-up')
		}else{
			loginBtn.parentNode.parentNode.classList.add('slide-up')
			parent.classList.remove('slide-up')
		}
	});
});
document.querySelector('.exit-btn-re').addEventListener('click', function (event) {
    event.preventDefault();

});
function saveInputValues() {
	// ตั้งค่าคีย์เฉพาะสำหรับแต่ละฟิลด์
	const firstnameKey = 'firstName';
	const lastnameKey = 'lastName';
	const emailKey = 'email';
	const numberKey = 'number';
  
	// เก็บค่าอินพุตไว้ใน localStorage
	localStorage.setItem(firstnameKey, document.getElementById('firstname').value);
	localStorage.setItem(lastnameKey, document.getElementById('lastname').value);
	localStorage.setItem(emailKey, document.getElementById('email').value);
	localStorage.setItem(numberKey, document.getElementById('number').value);
  }
	$(".submit-btn").click(saveInputValues);