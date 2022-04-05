// //alert('hi')

//const { basename } = require("node:path/win32");

// const { values } = require("lodash");
// const { callbackify } = require("util");

// // set thời gian chạy của một đoạn code ( một lần)
// setTimeout(function () {
//     console.log('set timeout')
// }, 1000)

// // set thơi gian chạy liên tục một đoạn code
// setInterval(function () {
//     console.log('set timeout')
// }, 1000)

// // Symbol => unique
// var id = Symbol('id');//unique
// var id2 = Symbol('id');//unique

// //Object types
// var myObject = {
//     name: 'Tra Nguyen',
//     age: 18,
//     addres: 'Nghe An',
//     myFunction: function () {

//     }
// }

// console.log('label', myObject)

// // array cũng là object, ko cần định nghĩa key

// var myArray = [
//     'Js',
//     'PHP'
// ]

// console.log(myArray)

// // Kiểm tra kiểu dữ liệu
// console.log(typeof myObject)

// /*
// 0, '', null, undefined, false, NaN
// */
// //đọc value bên phải
// var result = 'a' && 'b' && null && 'd'
// console.log(result); //null

// //
// var resultOr = 'a' || 'b' || null || 'd'
// console.log(resultOr); //a

// // javascript string methods
// /*lenght, 
// find index ( indexOf('string', position search); lastIndexOf)
// cut string: cắt chuỗi console.log(string.slice(4, 6))
// replace: ghi đè string.replace('JS', 'Javascript')
// Biểu thức chính quy để tìm tất cả chuỗi trong đoạn: /JS/g

// Trim: bỏ khoảng trắng đầu và cuối string.trim()
// Split: cắt chuỗi thành array( tìm điểm chung hợp lý)
// get a character by index: charAt
// */

// //isNaN kiểm tra có phải kiểu NaN không
// // array: Array.isArray(array)



// //Javascript array methods

// /**
//  * toString: mảng thành chuỗi
//  * Join: mảng thành chuỗi, có truyền ngăn cách
//  * Pop: xóa phần thử cuối mảng, trả lại chính phần tử đã xóa
//  * Push: thêm phần tử cuối mảng, trả độ dài mới của mảng
//  * Shift: Xóa phần tử đầu mảng, trả về phần tử đã xóa
//  * Unshift: Thêm một hoặc nhiều ở đầu mảng, trả về độ dài mới của mảng
//  * Splicing: Xóa, cắt, chèn phần tử mới vào mảng: languages.splice(positionStart, numberString, valueIfAdd)
//  * Concat: nối array array1.concat(array2)
//  * Slicing: cắt mảng array.slice(startPosition, endPosition)
//  */


// //Function in javascript
// /**
//  * đối tượng arguments chỉ gọi trong hàm, 
//  * nhận được tất cả các biến truyền vào hàm
//  */


// function writeLog() {
//     for (var param of arguments) {
//         console.log(param)
//     }
// }

// writeLog('Log1', 'Log2', 'Log3')

// /**
//  * declaration function: có thể gọi trước khi định nghĩa
//  * function show() {
//  * }
//  * expression function: không thể gọi trước khi định nghĩa
//  * setTimeout(function () {
//  * })
//  * var show = function () {
//  * }
//  */

// //Search Mozzila
// // Polyfill định nghĩa các tính năng mà trình duyệt cũ không hỗ trợ
// // Kiểm tra includes có tồn tại trong string không
// if (!String.prototype.includes) {
//     String.prototype.includes = function(search, start) {
//         'use strict';

//         // kiểm tra tính hợp lệ
//         if (search instanceof RegExp) {
//         throw TypeError('first argument must not be a RegExp');
//         }
//         if (start === undefined) { start = 0; }
//         return this.indexOf(search, start) !== -1;
//     };
// }


// //Object trong Javascript
// var myInfo = {
//     name: 'Tra Nguyen', 
//     age: 18,
//     address: "nghệ an",
//     getName: function() {
//         return this.name;
//     }
// }
// //function ->method
// //other -> property

// //add key
// myInfo.email = 'String';
// myInfo['my-email'] = 'String';
// delete myInfo.age


// // Object constructor
// // tạo một bản thiết kế chung
// function User(firstName, lastName, avatar) {
//     this.firstName = firstName;
//     this.lastName = lastName;
//     this.avatar = avatar;
//     this.getName = function () {
//         return `${this.firstName} ${this.lastName}`
//     }
// }

// //Object prototype
// /**
//  * prototype: nguyên mẫu
//  * object prototy: nguyên liệu tạo nên ngôi nhà ( constructor )
//  */

// //add property cho contructor
// // nằm trong __proto__
// User.prototype.className ='F8'
// User.prototype.getClassName = function () {
//     return this.className;
// }
// var author = new User('Tra', 'Nguyen', 'avatar');
// var user = new User('Tra1', 'Nguyen1', 'avatar1');

// author.title = 'đây là title'
// user.comment = 'đây là comment'



// // Đối tượng Date
// //javascript date object mozilla
// var date = new Date();//object
// var nowDate = Date();//string

// console.log(date.getFullYear())
// console.log(date.getMonth() + 1)

// /**
//  * for - lặp với điều kiện đúng
//  * for/in: lặp qua key của đối tượng
//  * for/of Lặp qua value của đối tượng
//  * while Lặp khi điều kiện đúng
//  * do/while Lặp ít nhất 1 lần, sau đó lặp khi điều kiện đúng.
//  */

// /**
//  * Array method
//  * forEach() duyệt hết
//  * every() duyệt hết, sai thì dừng lại
//  * some() chỉ cần một ông có đk đúng
//  * find() tìm kiếm => tìm ra đúng một phần tử thôi
//  * filter()
//  * map() => sửa mảng lọc qua, rồi trả về mảng mới.+. dùng khi hiển thị view ra màn hình
//  * reduce() => Nhận về kết quả duy nhất
//  */

// courses = [
//     {
//         id: 1,
//         name: 'JS', 
//         coin: 250
//     },
//     {
//         id: 2,
//         name: 'JS1', 
//         coin: 0
//     },
//     {
//         id: 3,
//         name: 'JS2', 
//         coin: 0
//     },
//     {
//         id: 4,
//         name: 'JS3', 
//         coin: 0
//     },
// ]
// //chạy qua hết các phần tử có trong mảng
// //forEach, every, some, find
// var isFree  = courses.forEach(function (course, index) {
//     return course.coin === 0 ;
// })
// console.log(isFree)

// //map => view ra màn hình
// function courseHandler(course, index) {
//     return `<h2>${course.name}</h2>`
// }
// var newCourse = courses.map(courseHandler);

// console.log(newCourse.join(''))

// //reduce => đưa ra một giá trị duy nhất ( tổng )

// function coinHandler(accumulator, currentValue, curentIndex, originArray) {
//     return accumulator + currentValue.coin;
// }
// var totalCoin = courses.reduce(coinHandler, 0);
// //0 giá trị khởi tạo, accumulator: biến lưu trữ ( lần chạy đầu tiên sẽ gán giá trị khởi tạo)
// // originArray và courses trỏ về cùng một vùng nhớ

// var totalCoin2 = courses.reduce((total, course) => total + course.coin, 0);

// var depthArray = [1, 2, [3, 4], 5]

// //làm phẳng mảng sâu
// var flatArray = depthArray.reduce(function(flatOutput, depthItem) {
//     return flatOutput.concat(depthItem)
// }, [])

// var topics = [
//     {
//         topic: 'font_end',
//         courses: [
//             {
//                 id: 1, 
//                 title: 'JS'
//             },
//             {
//                 id: 2, 
//                 title: 'JS2'
//             }
//         ]
//     },
//     {
//         topic: 'back_end',
//         courses: [
//             {
//                 id: 1, 
//                 title: 'PHP'
//             },
//             {
//                 id: 2, 
//                 title: 'PHP2'
//             }
//         ]
//     }
// ];

// var newCousesTopic = topics.reduce(function (courses, topic) {
//     return courses.concat(topic.courses)
// }, [])

// var htmls = newCousesTopic.map(function (course) {
//     return `
//     <div>
//         <h2>${course.title}</h2>
//     </div>
//     `;
// })

// console.log(htmls.join(''));

// //includes method
// /**
//  * String.prototype.includes
//  * String
//  * Array
//  */

// var title = 'Responsive web design';
// console.log(title.includes('web'))
// //True => có chuỗi đó tồn tại

// var courses1 = ['JS', 'PHP']
// console.log(courses1.includes('JS'))
// // tìm JS có trong mảng không, 1: positionStart

/**
 * Math object
 * Math.PI
 * Math.round() làm tròn số
 * Math.abs() số tuyệt đối
 * Math.ceil() làm tròn trên
 * Math.floor() làm tròn dưới
 * Math.random() random số thập phân nhỏ hơn 1 Math.floor(Math.random() * 10)
 * Math.min()
 * Math.max()
 */


//Callback: gọi lại, hàm được truyền qua đối số khi gọi hàm khác
//Gọi một hàm trong một hàm
// Được gọi lài trong hàm nhận đối số

// function myFunction1(param) {
//     param('Học lập trình');
// }

// function myCallback(values) {
//     return 'value : ' + values;
// }
// myFunction1(myCallback)

// //định nghĩa map2 
// Array.prototype.map2 = function (callback) {
//     //this chính là thằng gọi đến map2 này courses2.map2
//     var output = [];
//     //console.log(this)
//     var arrayLength = this.length;
//     for (var i = 0; i < arrayLength; ++i) {
//         var result = callback(this[i], i);
//         output.push(result);
//     }
//     return output;
// }
// var courses2 = [
//     'Javascript', 
//     'PHP', 
//     'Ruby'
// ];
 
// var htmls2 = courses2.map2(function (course, index) {
//     return `<h2>
//         ${course}
//     </h2>`;
// })

// // var htmls = courses2.map(function (course) {
// //     return `
// //     <h2>${course}</h2>
// //     `;
// // })
// console.log(htmls2.join(''));

// Array.prototype.forEach2 = function (callback) {
//     for (var index in this) {
//         if (this.hasOwnProperty(index)) {
//             callback(this[index], index, this)
//         }
//     }
// }
// //courses2 là một array, và nó được kế thừa các thuộc tính có trong Array constructor (prototype)
// courses2.length = 10000;
// var courses22 = courses2.forEach2(function(course, index, array) {
//     console.log(course, index, array)
// })

//filter
//value types and reference types

// var courses = [
//     {
//         name: 'Javascript',
//         coin: 680
//     },
//     {
//         name: 'Javascript1',
//         coin: 760
//     },
//     {
//         name: 'Javascript2',
//         coin: 800
//     }
// ];

// // var filterCourses = courses.filter(function (course, index, array) {
// //     return course.coin > 700;
// // })
// Array.prototype.filter2 = function(callback) {
//     var output = [];
//     for (var index in this) {
//         if (this.hasOwnProperty(index)) {
//             var result = callback(this[index], index, this)
//             if (result) {
//                 output.push(this[index])
//             }
//         }
//     }
//     return output;
// }
// var filterCourses = courses.filter2(function (course, index, array) {
//     return course.coin > 700;
// })

// console.log(filterCourses);


//HTML DOM
/**
 * có 3 thành phần
 * element: ID, class, tag, CSS selector, HTML collection
 * attribute: 
 * text
 * DOM dùng để thay đổi những thành phần trên
 */

//Javascript: Browser | Server
// document.write('Hello')

// //getElementById : chỉ có một id duy nhất -> trả về chỉ 1 element
// //getElementsByClassName: lấy được nhiều class HTML collection
// //getElementsByTagName HTML collection
// //querySelector -> trả về chỉ 1 element
// //querySelectorAll Node list
// //HTML collection ( forms, a, img)
// var headingNode = document.getElementById('heading');
// console.log({
//     element: headingNode
// })

// //CSS selector
// var headingNodeCss = document.querySelector('.heading');
// //querySelectorAll

// console.log(document.forms)

// //DOM attributes

// var headingElement = document.querySelector('h2');
// headingElement.title = 'Heading'
// headingElement.id = 'heading2'
// //set
// headingElement.setAttribute('class', 'heading2')
// //get
// console.log(headingElement.getAttribute('class'))

// //innerText, textContent

// var headingElement2 = document.getElementById('heading2');
// console.log(headingElement2.innerText)
// headingElement2.innerText = 'New heading1'
// // console.log(headingElement2.textContent)
// // headingElement2.textContent = 'New heading2'
// //textContent: lấy giá trị thật của DOM
// //innerText: trả ra giống như những gì nhìn thấy


// //innerHTML, outerHTML
// var boxElement = document.querySelector('.box');

// // boxElement.innerHTML = '<h1>Heading</h1>'
// // console.log([boxElement])

// console.log(boxElement.style)


//ClassList property
/**
 * add
 * contains
 * remove
 * toggle
 */
// console.log(boxElement.classList)
// //add class
// console.log(boxElement.classList.add('red'))
// //contains kiểm tra class có tồn tại không
// console.log(boxElement.classList.contains('red'))
// // remove class
// console.log(boxElement.classList.remove('red'))
// //toggle khi đoạn mã đó chạy, nếu có class đó thì xóa, ko có thì thêm vào
// console.log(boxElement.classList.toggle('red'))
// setInterval(() => {
//     console.log(boxElement.classList.toggle('red'))
// }, 1000)

// DOM events
/**
 * Attribute events
 */

// var h1Element = document.querySelectorAll('h1');

// console.log(h1Element)
// for (var i = 0; i < h1Element.length; ++i) {
//     h1Element[i].onclick = function (e) {
//         //target chính là element đang lắng nghe
//         console.log(e.target);
//     }
// }

/**
 * Input/select
 * Key up
 */
// var inputValue;
// //var inputElement = document.querySelector('input[type="text"]');
// //var inputElement = document.querySelector('input[type="checkbox"]');
// var inputElement = document.querySelector('select');
// //onchange lấy giá trị thay đổi sau khi gõ xong, oninput: lấy khi đang gõ
// inputElement.onchange = function(e) {
//     console.log(e.target.value);
// }
//console.log(inputValue)

//onkeydown => 
//onkeyup => in ra luôn ( nhấc lên)
//e.which => in ra phím đó là số mấy


/**
 * preventDefault: ngăn chạn hành vi mặc định
 * stopPropagation: ngăn chặn hành vi lan truyền
 */

// var aElements = document.querySelectorAll('a')
// for (var i = 0; i < aElements.length; ++i) {
//     aElements[i].onclick = function(e) {
//         if (!e.target.href.startWith('http://f8.edu.vn')) {
//             e.preventDefault();
//         }
//     }
// }


// console.log(aElements)


// var divElement = document.querySelector('div');
// divElement.onclick = function() {
//     console.log('DIV');
// }

// document.querySelector('div').onclick = function () {
//     console.log('DIV')
// }

// var clickElement = document.querySelector('button');
// clickElement.onclick = function(e) {
//     e.stopPropagation();
//     console.log('Click me')
// }


/**
 * DOM event: xử lý một việc mà không có ý định hủy
 * Event Listener: một sự kiện diễn ra mà muốn hủy trong một trường hợp nào đó ( addEventListener, removeEventListener)
 * JSON
 * Fetch
 * DOM location
 * Local storage
 * Session storage
 * Conding convention
 * Best Practices
 * Mistakes
 * Performance
 */

/**
 * Xử lý nhiều việc khi 1 event xảy ra
 * Lắng nghe / Hủy bỏ lắng nghe
 */

// var btn = document.getElementById('btn')

// function viec1(){
//     console.log('Việc 1')
// }

// function viec2(){
//     console.log('Việc 2')
// }

// btn.addEventListener('click', viec1)

// btn.addEventListener('click', viec2)

// setTimeout(() => {
//     btn.removeEventListener('click', viec1)
// }, 3000)

/**
 * JSON: là định dạng dữ liệu (chuỗi)
 * JSON: Javascript Object Notation
 * Number, Boolean, Null, Array, Object, String
 * Mã hóa (Encode) / Giải mã (Decode)
 * Stringify Javascript => JSON
 * Parse JSON =>JS
 */

// var json = '{"name":"Tra Nguyen", "age": 18}';
// var a = '"abc"';
// //JSON=>JS
// console.log(JSON.parse(json))
// //JS=>JSON
// console.log(JSON.stringify([
//     'JS',
//     'PHP'
// ]))

/**
 * Promise: Xử lý thao tác bất đồng bộ, khắc phục tình trạng callback hell
 * Sync: đồng bộ
 * Async: bất đồng bộ
 * pain: NỖi đâu
 * cách hoạt động
 */

//sync chạy theo luồng viết trước chạy trước, viết sau chạy sau
//async bất đồng bộ: (chạy không theo luồng, viết trước mà in sau): setTimeout, setInterval, fetch, XMLHttpRequest, file reading
//request animation frame

//Nỗi đau: 
/**
 * callback hell ( tác vụ phụ thuộc vào nhau, tác vụ sau cần tác vụ trước hoàn thành thì mới thực thi được)
 * tác vụ lồng tác vụ
 * pyramid of dom
 */

// var promise = new Promise(
//     //Executor
//     function(resolve, reject) {
//         //Logic
//         //Thành công: gọi resolve()
//         //Thất bại: reject()

//         //Fake call API

//         resolve();
//     }
// );

// /**
//  * pendding
//  * fulfiled
//  * reject
//  */
// promise
//     .then(function() {
//         //resolve
//         //console.log('Thành công')
//         return 1;
//     })
//     .then(function(data) {
//         //resolve
//         console.log(data)
//         return 2;
//     })
//     .then(function(data) {
//         //resolve
//         console.log(data)
//     })
//     .catch(function() {
//         //reject
//         console.log('Thất bại')
//     })
//     .finally(function() {
//         console.log('Done')
//     });

// /**
//  * Promise.resolve
//  * Promise.reject
//  * Promise.all
//  */

// var promise1 = new Promise(
//     function (resolve) {
//         setTimeout(function() {
//             resolve([1])
//         }, 2000)
//     }
// )

// var promise2 = new Promise(function(resolve) {
//     setTimeout(function() {
//         resolve([2, 3])
//     }, 3000)
// })

// Promise.all([promise1, promise2])
//     .then(function(result) {
//         console.log(result)
//     })

/**
 * ECMAScript6 ES6
 * Let, const
 * Template Literals
 * Multi-line string
 * arrow function 
 * classes
 * default paramater values
 * destructuring
 * Rest parameters
 * Spread
 * Enhanced object literals
 * Tagged template literals
 * Modules
 */

/**
 * var/let, const(local): Scope, Hosting
 * const(không sử dụng gán toán tử lần thứ 2 cho biến)/var, let: assignment
 * hoisting: đưa lên đầu var a = 1 => var a, a = 1
 */

/**
 * arrow function 
 */

// const logger = (a, b) => a + b;
// console.log(logger(2, 2));

// const sum = (a, b) => ({a: a, b: b})

/**
 * Promise
 */

// var comments = [
//     {
//         id: 1, 
//         user_id: 1,
//         content: 'Ra video chưa ban e'
//     },
//     {
//         id: 2, 
//         user_id: 2,
//         content: 'Chưa nhé'
//     },
//     {
//         id: 3, 
//         user_id: 1,
//         content: 'Ok ban'
//     }
// ];

// var users = [
//     {
//         id: 1, 
//         name: 'Tra 1'
//     },
//     {
//         id: 2, 
//         name: 'Tra Nguyen'
//     },
//     {
//         id: 3, 
//         name: 'Tra 3'
//     }
// ];
// //fake API
// function getComments() {
//     return new Promise(function(resolve) {
//         setTimeout(() => {
//             resolve(comments);
//         }, 1000)
//     })
// }

// function getUsersByIds(userIds) {
//     return new Promise(function(resolve) {
//         var result = users.filter(function (user) {
//             return userIds.includes(user.id);
//         })
//         resolve(result);
//     })
// }
// getComments()
//     .then(function(comments) {
//         var userIds = comments.map(function(comment) {
//             return comment.user_id
//         })

//         return getUsersByIds(userIds)
//             .then(function(users) {
//                 return {
//                     users: users,
//                     comments: comments
//                 };
//             })
//     })
//     .then(function(data) {
//         var commentBlock = document.getElementById('test-comment');
//         var htmls = '';
//         data.comments.forEach(function(comment) {
//             var user = data.users.find(function (user) {
//                 return user.id === comment.user_id;
//             })
//             htmls += `<li>${user.name} : ${comment.content}</li>`;
//         });
//         commentBlock.innerHTML = htmls;
//     });



/**
 * API => applicaton programing interface
 * cổng giao tiếp giữa các phần mềm
 * Backend ->API -> Fetch -> JSON ->
 * JSON.parse => JS
 * Render ra giao diện với HTML
 */

// var postApi = 'https://jsonplaceholder.typicode.com/posts';

// fetch(postApi) 
//     .then(function(response) {
//         return response.json();
//         //JSON.parse: JSON -> JS types
//     })
//     .then(function(posts) {
//         console.log(posts)
//         var htmls = posts.map(function(post) {
//             return `<li>${post.title}</li>`
//         })

//         htmls.join('')
//         document.getElementById('post-block').innerHTML = htmls
//     })
//     .catch(function(err) {
//         alert('có lỗi')
//     })

/**
 * JSON serve: API server
 */

/**
 * CRUD
 * create: POST
 * Read: GET
 * update: PUT/PATCH
 * delete: DELETE
 */

//Ex



// var auctionApi = 'http://localhost:8080/api/auctions';

// function start() {
//     getAuctions(function(auctions) {
//         renderAuctions(auctions);
//     });
//     handleCreateForm();
//     handlerDeleteAuction();
// }

// start();

// var auctionCreateApi = 'http://localhost:8080/api/auctions/create';
// function getAuctions(callback) {
//     fetch(auctionApi) 
//         .then(function(response) {
//             return response.json();
//         })
//         .then(callback);
// }

// function createAuction(data, callback) {
//     var options = {
//         method: 'POST', 
//         headers: {
//             'Content-Type': 'application/json'
//         },
//         body: JSON.stringify(data),
//     }
//     fetch(auctionCreateApi, options)
//         .then(function(response) {
//             response.json();
//         })
//         .then(callback)
// }

// function handlerDeleteAuction(id) {
//     var options = {
//         method: 'DELETE', 
//         headers: {
//             'Content-Type': 'application/json'
//         }
//     }
//     fetch(auctionApi + '/' + id, options)
//         .then(function(response) {
//             response.json();
//         })
//         //gỡ khỏi DOM khi xóa
//         .then(function() {
//             var auctionItem = document.querySelector('.auction-item-' + id);
//             if (auctionItem) {
//                 auctionItem.remove();
//             }
//         })
// }
// function renderAuctions(auctions) {
//     var auctionBlock = document.getElementById('list-auctions');
//     var auctionss = auctions.data.auctions;
//     var htmls = auctionss.map(function(auction) {
//         return `
//             <li class="auction-item-${auction.auction_id}">
//                 <h4>${auction.title}</h4>
//                 <button onclick="handlerDeleteAuction(${auction.auction_id})">Xóa</button>
//             </li>
//         `;
//     })
//     auctionBlock.innerHTML = htmls.join('')
// }

// function handleCreateForm() {
//     var createBtn = document.getElementById('create')
//     createBtn.onclick = function() {
//         var name = document.querySelector('input[name="name"]').value;
//         var formData = {
//             name: name,
//         }
//         createAuction(formData, function(){
//             // getAuctions(function(auctions) {
//             //     renderAuctions(auctions);
//             // });
//             //load lại trang luôn ( trong comment )
//             getAuctions(renderAuctions);
//         })
//     }
// }


/**
 * ECMAScript6
 */

//Enhanced Object

// var fieldName = 'new-name';
// var fieldPrice = 'price';

// const course = {
//     [fieldName]: 'Tra',
//     [fieldPrice]: '10000'
// };

// console.log(course);

// //Destructuting, Rest: Phân rã, dùng với object và array
// //Rest lấy ra phần còn lại ( định nghĩa ra tham số)

// var array = ['Javascript', 'PHP', 'Ruby'];

// var [a, b, c] = array;
// var [a, , c] = array;
// var [a] = array;
// var [a, ...rest] = array;

// console.log(rest);

// var course1 = {
//     name: 'Javascript', 
//     price: 2000, 
//     children: {
//         name: 'ReactJs'
//     }
// };

// var { name, price } = course1;
// console.log(name, price);
// var { name: newName, children: {name}} = course1;
// console.log(newName);

// function restFunction(...rest) {
//     console.log(...rest)
// }

// restFunction(1, 2, 3, 4)

// //Spread ( chuyển đổi tham số)
// var array1 = ['JS', 'Ruby', 'Java'];
// var array2 = ['Dart', 'PHP'];
// var array3 = [...array1, ...array2];

// console.log(array3);

// var defaultConfig = {
//     api: 'http://localhost:8080',
//     apiV: 'v1',
//     description: 'description content'
// }

// var exerciseConfig = {
//     ...defaultConfig,
//     api: 'http://localhost:8080//exercise'
// }

// console.log(exerciseConfig);


// function logger(...rest) {
//     console.log(...rest)
// }

// logger(...array3);


//Modules: Import/Export

// import logger from './logger.js';
// import {
//     TYPE_WARNING,
//     TYPE_ERROR,
//     TYPE_LOG
// } from './constants.js'

// console.log(logger);

// logger('Test messages', TYPE_ERROR)


//React js
