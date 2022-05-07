const form_tag = document.querySelector("#form")
const input_file_tag = document.querySelector("#input-upload-image")
const button_submit_tag = document.querySelector("#button-submit")

const prepare_summary_tag = document.querySelector("#prepare-summary")
const uploaded_summary_tag = document.querySelector("#uploaded-summary")
const uploaded_images_tag = document.querySelector("#uploaded-images")

const uploaded_images_template_content = document.querySelector("#uploaded-images-template").content.cloneNode(true)
const uploaded_images_figcaption = uploaded_images_template_content.querySelector("figcaption")
const uploaded_images_target = uploaded_images_template_content.querySelector("img")
const uploaded_images_size = uploaded_images_template_content.querySelector(".uploaded-images__item-size-value")

const msg_class_error = "msg_error"
const msg_class_success = "msg_success"

const form_data = new FormData()

const generate_msg = (status, text, text_addition = '') => {
	const msg_template_content = document.querySelector("#msg-template").content.cloneNode(true)
	const msg_content = msg_template_content.querySelector(".msg__content")
	const msg_content_addition = msg_template_content.querySelector(".msg__addition")
	msg_template_content.children[0].classList.add(status)
	msg_content.innerHTML = text
	
	text_addition.length ? msg_content_addition.innerHTML = text_addition : msg_content_addition.remove()
	return msg_template_content
}

const check = (files) => {
	console.log(form_data)
	prepare_summary_tag.innerHTML = ""
	
	for (let i = 0; i < files.length; i++) {
		if (!['image/jpeg', 'image/png'].includes(files[i].type)) {
			prepare_summary_tag.append(generate_msg(msg_class_error, `file <b>${files[i].name}</b> is no image!`))
		} else if (files[i].size > 2 * 1024 * 1024) {
			prepare_summary_tag.append(generate_msg(msg_class_error, `file <b>${files[i].name}</b> is no image!`))
		} else {
			prepare_summary_tag.append(generate_msg(msg_class_success, `file <b>${files[i].name}</b> prepare to adding!`, `(${files[i].size} bytes)`))
			form_data.append(`image_${i}`, files[i])
		}
	}
}

const upload_image = (e) => {
	e.preventDefault()
	
	if (![...form_data].length) {
		prepare_summary_tag.replaceChildren(generate_msg(msg_class_error, "dont check files!"))
		return false
	}
	
	const form_data_input = new FormData(form_tag)
	
	//console.log([...form_data_input])
	
	form_data_input.forEach((key, item) => {
		form_data.append(item, key)
		//console.log(item)
		//console.log(key)
	})
	
	form_data.delete("input-upload-image")
	
	
	
	//console.log([...form_data])
	
	
	
	//form_data.delete('image_0') //for testing backend exception
	uploaded_images_tag.innerHTML = "...loading..."
	button_submit_tag.disabled = true
	const backend_url_base = "http://localhost/javascript_learning__upload_files/backend"
	fetch(`${backend_url_base}/upload.php`, {
		method: "POST",
		body: form_data
	})
	/*
	//alternative variant
	.then(async response => {
			try {
					const data = await response.json()
					return data
			} catch(error) {
					throw new Error("error while json parsing!")
			}
	})
	*/
	.then(response => {
		return data = new Promise((resolve, reject) => {
			response.json()
			.then((data) => resolve(data))
			.catch((err) => reject(new Error("error while json parsing!")))
		})
	})
	.then((responseData) => {
		if (responseData.images_array) {
			uploaded_images_tag.innerHTML = ""
			responseData.images_array.forEach(item => {
				uploaded_images_figcaption.textContent = item.image_source
				uploaded_images_target.src = `${backend_url_base}/${item.image_source}`
				uploaded_images_target.setAttribute("alt", item.image_source)
				uploaded_images_size.textContent = item.file_size
				uploaded_images_tag.append(uploaded_images_template_content.cloneNode(true))
			});
			button_submit_tag.disabled = false
			return
		}
		throw new Error(responseData.error_name)
	})
	.catch((err) => {
		console.log(err)
		button_submit_tag.disabled = false
		uploaded_summary_tag.innerHTML = ""
		uploaded_summary_tag.append(generate_msg(msg_class_error, err))
		uploaded_images_tag.innerHTML = ""
	})
}


input_file_tag.addEventListener("change", () => {
	check(input_file_tag.files)
})

button_submit_tag.addEventListener("click", upload_image  )