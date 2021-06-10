(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[1],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/spa/UnreadNotificationsComponent.vue?vue&type=script&lang=js&":
/*!*******************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/spa/UnreadNotificationsComponent.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/regenerator */ "./node_modules/@babel/runtime/regenerator/index.js");
/* harmony import */ var _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var vuex__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! vuex */ "./node_modules/vuex/dist/vuex.esm.js");


function asyncGeneratorStep(gen, resolve, reject, _next, _throw, key, arg) { try { var info = gen[key](arg); var value = info.value; } catch (error) { reject(error); return; } if (info.done) { resolve(value); } else { Promise.resolve(value).then(_next, _throw); } }

function _asyncToGenerator(fn) { return function () { var self = this, args = arguments; return new Promise(function (resolve, reject) { var gen = fn.apply(self, args); function _next(value) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "next", value); } function _throw(err) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "throw", err); } _next(undefined); }); }; }

function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(Object(source), true).forEach(function (key) { _defineProperty(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//import MessageComponent from "../MessageComponent.vue";

/* harmony default export */ __webpack_exports__["default"] = ({
  //components: { MessageComponent },
  props: ['action'],
  computed: _objectSpread({}, Object(vuex__WEBPACK_IMPORTED_MODULE_1__["mapState"])({
    auth: function auth(state) {
      return state.AuthUser.auth;
    },
    auth_id: function auth_id(state) {
      return state.AuthUser.id;
    }
  })),
  created: function created() {
    if (localStorage.getItem('passport_token')) {
      this.$store.dispatch("getUserInfo");
    }
  },
  data: function data() {
    return {
      message: "",
      links: "",
      success_response: false,
      username: "",
      password: "",
      status: 'success',
      msg: "登陆成功",
      total_nums: 0,
      per_page: 0,
      cur_page: 0
    };
  },
  methods: {
    setData: function setData(data) {
      this.message = data.items;
      this.per_page = parseInt(data.page.per_page);
      this.cur_page = parseInt(data.page.current_page);
      this.total_nums = parseInt(data.page.total);
    },
    page_change: function page_change(page) {
      var _this2 = this;

      return _asyncToGenerator( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default.a.mark(function _callee() {
        var submit_data, config, url, res, _this, status, data;

        return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default.a.wrap(function _callee$(_context) {
          while (1) {
            switch (_context.prev = _context.next) {
              case 0:
                _this2.cur_page = page;
                submit_data = {
                  "page": _this2.cur_page
                };
                config = {};
                url = "";

                if (_this2.action == "all_message") {
                  url = "/spa/read_all_notifications";
                } else {
                  url = "/spa/read_notifications";
                }

                _context.next = 7;
                return axios.post(url, submit_data, config);

              case 7:
                res = _context.sent;
                _this = _this2;
                status = res.status, data = res.data;

                if (status == "200") {
                  // _this.nextTick(function(){
                  _this2.setData(data); // });

                }

              case 11:
              case "end":
                return _context.stop();
            }
          }
        }, _callee);
      }))();
    }
  },
  create: function create() {
    return _asyncToGenerator( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default.a.mark(function _callee2() {
      return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default.a.wrap(function _callee2$(_context2) {
        while (1) {
          switch (_context2.prev = _context2.next) {
            case 0:
            case "end":
              return _context2.stop();
          }
        }
      }, _callee2);
    }))();
  },
  mounted: function mounted() {
    var _this3 = this;

    return _asyncToGenerator( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default.a.mark(function _callee3() {
      var url, submit_data, config, res, status, data, _this;

      return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default.a.wrap(function _callee3$(_context3) {
        while (1) {
          switch (_context3.prev = _context3.next) {
            case 0:
              url = "";

              if (_this3.action == "all_message") {
                url = "/spa/read_all_notifications";
              } else {
                url = "/spa/read_notifications";
              }

              submit_data = {};
              config = {};
              _context3.next = 6;
              return axios.post(url, submit_data, config);

            case 6:
              res = _context3.sent;
              status = res.status, data = res.data;
              _this = _this3;

              if (data.code == "0") {
                _this3.setData(data);
              }

            case 10:
            case "end":
              return _context3.stop();
          }
        }
      }, _callee3);
    }))();
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/spa/UnreadNotificationsComponent.vue?vue&type=template&id=bb94d60c&":
/*!***********************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/spa/UnreadNotificationsComponent.vue?vue&type=template&id=bb94d60c& ***!
  \***********************************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "render", function() { return render; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return staticRenderFns; });
var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "div",
    [
      _vm.auth && _vm.message
        ? _c("spaheader-component", {
            ref: "headerCom",
            attrs: { is_login: _vm.auth }
          })
        : _vm._e(),
      _vm._v(" "),
      _c(
        "div",
        { staticClass: "container" },
        [
          _c("notificationlist-component", {
            attrs: {
              message: _vm.message,
              request_url: "/spa/mark_read",
              redirect_url: "",
              status: "success",
              msg: "标记成功"
            }
          }),
          _vm._v(" "),
          _c(
            "div",
            { staticStyle: { "margin-top": "30px" } },
            [
              _c("el-pagination", {
                attrs: {
                  background: "",
                  layout: "prev, pager, next",
                  total: _vm.total_nums,
                  "page-size": _vm.per_page,
                  "current-page": _vm.cur_page
                },
                on: {
                  "update:currentPage": function($event) {
                    _vm.cur_page = $event
                  },
                  "update:current-page": function($event) {
                    _vm.cur_page = $event
                  },
                  "current-change": _vm.page_change
                }
              })
            ],
            1
          )
        ],
        1
      )
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./resources/js/components/spa/UnreadNotificationsComponent.vue":
/*!**********************************************************************!*\
  !*** ./resources/js/components/spa/UnreadNotificationsComponent.vue ***!
  \**********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _UnreadNotificationsComponent_vue_vue_type_template_id_bb94d60c___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./UnreadNotificationsComponent.vue?vue&type=template&id=bb94d60c& */ "./resources/js/components/spa/UnreadNotificationsComponent.vue?vue&type=template&id=bb94d60c&");
/* harmony import */ var _UnreadNotificationsComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./UnreadNotificationsComponent.vue?vue&type=script&lang=js& */ "./resources/js/components/spa/UnreadNotificationsComponent.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _UnreadNotificationsComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _UnreadNotificationsComponent_vue_vue_type_template_id_bb94d60c___WEBPACK_IMPORTED_MODULE_0__["render"],
  _UnreadNotificationsComponent_vue_vue_type_template_id_bb94d60c___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/spa/UnreadNotificationsComponent.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/spa/UnreadNotificationsComponent.vue?vue&type=script&lang=js&":
/*!***********************************************************************************************!*\
  !*** ./resources/js/components/spa/UnreadNotificationsComponent.vue?vue&type=script&lang=js& ***!
  \***********************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_UnreadNotificationsComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./UnreadNotificationsComponent.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/spa/UnreadNotificationsComponent.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_UnreadNotificationsComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/spa/UnreadNotificationsComponent.vue?vue&type=template&id=bb94d60c&":
/*!*****************************************************************************************************!*\
  !*** ./resources/js/components/spa/UnreadNotificationsComponent.vue?vue&type=template&id=bb94d60c& ***!
  \*****************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_UnreadNotificationsComponent_vue_vue_type_template_id_bb94d60c___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./UnreadNotificationsComponent.vue?vue&type=template&id=bb94d60c& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/spa/UnreadNotificationsComponent.vue?vue&type=template&id=bb94d60c&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_UnreadNotificationsComponent_vue_vue_type_template_id_bb94d60c___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_UnreadNotificationsComponent_vue_vue_type_template_id_bb94d60c___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);