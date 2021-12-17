/*
 * ATTENTION: An "eval-source-map" devtool has been used.
 * This devtool is neither made for production nor for readable output files.
 * It uses "eval()" calls to create a separate source file with attached SourceMaps in the browser devtools.
 * If you are trying to read the output file, select a different devtool (https://webpack.js.org/configuration/devtool/)
 * or disable the default devtool with "devtool: false".
 * If you are looking for production-ready output files, see mode: "production" (https://webpack.js.org/configuration/mode/).
 */
/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./resources/js/delete-medical.js":
/*!****************************************!*\
  !*** ./resources/js/delete-medical.js ***!
  \****************************************/
/***/ (() => {

eval("$(function () {\n  $('.delete-medical').click(function () {\n    Swal.fire({\n      title: 'Czy na pewno chcesz usunąć lek?',\n      icon: 'warning',\n      showCancelButton: true,\n      confirmButtonText: 'Tak',\n      cancelButtonText: 'Anuluj'\n    }).then(function (result) {\n      if (result.value) {\n        $.ajax({\n          headers: {\n            'X-CSRF-TOKEN': $('meta[name=\"csrf-token\"]').attr('content')\n          },\n          method: \"POST\",\n          url: deleteUrlMedical,\n          data: {}\n        }).done(function (data) {\n          $('#validation-message-medical').append('<div class=\"alert dark alert-success alert-dismissible\" role=\"alert\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">×</span> </button><b>' + data.message + '</b></div>');\n          setTimeout(function () {\n            window.location.reload(true);\n          }, 2500);\n        }).fail(function (data) {\n          Swal.fire('Oops...', data.responseJSON.message, 'error');\n        });\n      }\n    });\n  });\n});//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9yZXNvdXJjZXMvanMvZGVsZXRlLW1lZGljYWwuanM/ZTQyNSJdLCJuYW1lcyI6WyIkIiwiY2xpY2siLCJTd2FsIiwiZmlyZSIsInRpdGxlIiwiaWNvbiIsInNob3dDYW5jZWxCdXR0b24iLCJjb25maXJtQnV0dG9uVGV4dCIsImNhbmNlbEJ1dHRvblRleHQiLCJ0aGVuIiwicmVzdWx0IiwidmFsdWUiLCJhamF4IiwiaGVhZGVycyIsImF0dHIiLCJtZXRob2QiLCJ1cmwiLCJkZWxldGVVcmxNZWRpY2FsIiwiZGF0YSIsImRvbmUiLCJhcHBlbmQiLCJtZXNzYWdlIiwic2V0VGltZW91dCIsIndpbmRvdyIsImxvY2F0aW9uIiwicmVsb2FkIiwiZmFpbCIsInJlc3BvbnNlSlNPTiJdLCJtYXBwaW5ncyI6IkFBQUFBLENBQUMsQ0FBQyxZQUFXO0FBQ1RBLEVBQUFBLENBQUMsQ0FBQyxpQkFBRCxDQUFELENBQXFCQyxLQUFyQixDQUEyQixZQUFXO0FBQ2xDQyxJQUFBQSxJQUFJLENBQUNDLElBQUwsQ0FBVTtBQUNOQyxNQUFBQSxLQUFLLEVBQUUsaUNBREQ7QUFFTkMsTUFBQUEsSUFBSSxFQUFFLFNBRkE7QUFHTkMsTUFBQUEsZ0JBQWdCLEVBQUUsSUFIWjtBQUlOQyxNQUFBQSxpQkFBaUIsRUFBRSxLQUpiO0FBS05DLE1BQUFBLGdCQUFnQixFQUFFO0FBTFosS0FBVixFQU1HQyxJQU5ILENBTVEsVUFBQ0MsTUFBRCxFQUFZO0FBQ2hCLFVBQUdBLE1BQU0sQ0FBQ0MsS0FBVixFQUFpQjtBQUNiWCxRQUFBQSxDQUFDLENBQUNZLElBQUYsQ0FBTztBQUNIQyxVQUFBQSxPQUFPLEVBQUU7QUFDTCw0QkFBZ0JiLENBQUMsQ0FBQyx5QkFBRCxDQUFELENBQTZCYyxJQUE3QixDQUFrQyxTQUFsQztBQURYLFdBRE47QUFJSEMsVUFBQUEsTUFBTSxFQUFFLE1BSkw7QUFLSEMsVUFBQUEsR0FBRyxFQUFFQyxnQkFMRjtBQU1IQyxVQUFBQSxJQUFJLEVBQUU7QUFOSCxTQUFQLEVBU0NDLElBVEQsQ0FTTSxVQUFTRCxJQUFULEVBQWM7QUFDaEJsQixVQUFBQSxDQUFDLENBQUMsNkJBQUQsQ0FBRCxDQUFpQ29CLE1BQWpDLENBQXdDLG9NQUFvTUYsSUFBSSxDQUFDRyxPQUF6TSxHQUFtTixZQUEzUDtBQUNBQyxVQUFBQSxVQUFVLENBQUMsWUFBWTtBQUFFQyxZQUFBQSxNQUFNLENBQUNDLFFBQVAsQ0FBZ0JDLE1BQWhCLENBQXVCLElBQXZCO0FBQStCLFdBQTlDLEVBQWdELElBQWhELENBQVY7QUFDSCxTQVpELEVBYUNDLElBYkQsQ0FhTSxVQUFTUixJQUFULEVBQWM7QUFDaEJoQixVQUFBQSxJQUFJLENBQUNDLElBQUwsQ0FBVSxTQUFWLEVBQXFCZSxJQUFJLENBQUNTLFlBQUwsQ0FBa0JOLE9BQXZDLEVBQWdELE9BQWhEO0FBQ0gsU0FmRDtBQWdCSDtBQUNKLEtBekJEO0FBMEJILEdBM0JEO0FBNEJILENBN0JBLENBQUQiLCJzb3VyY2VzQ29udGVudCI6WyIkKGZ1bmN0aW9uKCkge1xyXG4gICAgJCgnLmRlbGV0ZS1tZWRpY2FsJykuY2xpY2soZnVuY3Rpb24oKSB7XHJcbiAgICAgICAgU3dhbC5maXJlKHtcclxuICAgICAgICAgICAgdGl0bGU6ICdDenkgbmEgcGV3bm8gY2hjZXN6IHVzdW7EhcSHIGxlaz8nLFxyXG4gICAgICAgICAgICBpY29uOiAnd2FybmluZycsXHJcbiAgICAgICAgICAgIHNob3dDYW5jZWxCdXR0b246IHRydWUsXHJcbiAgICAgICAgICAgIGNvbmZpcm1CdXR0b25UZXh0OiAnVGFrJyxcclxuICAgICAgICAgICAgY2FuY2VsQnV0dG9uVGV4dDogJ0FudWx1aidcclxuICAgICAgICB9KS50aGVuKChyZXN1bHQpID0+IHtcclxuICAgICAgICAgICAgaWYocmVzdWx0LnZhbHVlKSB7XHJcbiAgICAgICAgICAgICAgICAkLmFqYXgoe1xyXG4gICAgICAgICAgICAgICAgICAgIGhlYWRlcnM6IHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgJ1gtQ1NSRi1UT0tFTic6ICQoJ21ldGFbbmFtZT1cImNzcmYtdG9rZW5cIl0nKS5hdHRyKCdjb250ZW50JylcclxuICAgICAgICAgICAgICAgICAgICB9LFxyXG4gICAgICAgICAgICAgICAgICAgIG1ldGhvZDogXCJQT1NUXCIsXHJcbiAgICAgICAgICAgICAgICAgICAgdXJsOiBkZWxldGVVcmxNZWRpY2FsLFxyXG4gICAgICAgICAgICAgICAgICAgIGRhdGE6IHtcclxuICAgICAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICB9KVxyXG4gICAgICAgICAgICAgICAgLmRvbmUoZnVuY3Rpb24oZGF0YSl7XHJcbiAgICAgICAgICAgICAgICAgICAgJCgnI3ZhbGlkYXRpb24tbWVzc2FnZS1tZWRpY2FsJykuYXBwZW5kKCc8ZGl2IGNsYXNzPVwiYWxlcnQgZGFyayBhbGVydC1zdWNjZXNzIGFsZXJ0LWRpc21pc3NpYmxlXCIgcm9sZT1cImFsZXJ0XCI+PGJ1dHRvbiB0eXBlPVwiYnV0dG9uXCIgY2xhc3M9XCJjbG9zZVwiIGRhdGEtZGlzbWlzcz1cImFsZXJ0XCIgYXJpYS1sYWJlbD1cIkNsb3NlXCI+PHNwYW4gYXJpYS1oaWRkZW49XCJ0cnVlXCI+w5c8L3NwYW4+IDwvYnV0dG9uPjxiPicgKyBkYXRhLm1lc3NhZ2UgKyAnPC9iPjwvZGl2PicpO1xyXG4gICAgICAgICAgICAgICAgICAgIHNldFRpbWVvdXQoZnVuY3Rpb24gKCkgeyB3aW5kb3cubG9jYXRpb24ucmVsb2FkKHRydWUpOyB9LCAyNTAwKTtcclxuICAgICAgICAgICAgICAgIH0pXHJcbiAgICAgICAgICAgICAgICAuZmFpbChmdW5jdGlvbihkYXRhKXtcclxuICAgICAgICAgICAgICAgICAgICBTd2FsLmZpcmUoJ09vcHMuLi4nLCBkYXRhLnJlc3BvbnNlSlNPTi5tZXNzYWdlLCAnZXJyb3InKTtcclxuICAgICAgICAgICAgICAgIH0pO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgfSk7XHJcbiAgICB9KTtcclxufSk7Il0sImZpbGUiOiIuL3Jlc291cmNlcy9qcy9kZWxldGUtbWVkaWNhbC5qcy5qcyIsInNvdXJjZVJvb3QiOiIifQ==\n//# sourceURL=webpack-internal:///./resources/js/delete-medical.js\n");

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module can't be inlined because the eval-source-map devtool is used.
/******/ 	var __webpack_exports__ = {};
/******/ 	__webpack_modules__["./resources/js/delete-medical.js"]();
/******/ 	
/******/ })()
;