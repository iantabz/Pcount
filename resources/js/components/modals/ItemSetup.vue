<!-- @format -->

<template>
  <div>
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h3
            class="panel-heading bord-btm text-thin"
            id="mdlTitle"
            style="font-size: 20px;"
          >
            <i class="demo-pli-printer icon-lg"></i>
            {{ editItem == null ? 'Add Item(s)' : 'Edit Item' }}
          </h3>
          <button
            type="button"
            class="close"
            data-dismiss="modal"
            aria-label="Close"
            @click="closeBtn()"
          >
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form class="form-horizontal" @submit.prevent>
            <div class="panel-body">
              <div class="row" style="padding: 10px 15px 15px 10px">
                <div v-if="editItem == null">
                  <label
                    class="col-md-3 control-label text-thin"
                    style="text-align: right"
                  >
                    <h5>Item :</h5>
                  </label>
                  <div class="col-md-6">
                    <v-select
                      id="demo-oi-definput"
                      v-model.trim="item"
                      :filterable="false"
                      @search="retrieveCategory"
                      label="item"
                      :options="categoryList"
                      placeholder="Search Item using Item Code / Barcode"
                      ><template slot="no-options">
                        <strong>Search Item using Item Code / Barcode</strong>
                      </template>
                      <template slot="option" slot-scope="option">
                        <em style="margin: 0">
                          {{
                            `${option.item_code + ' ' + option.extended_desc} `
                          }}
                        </em>
                        <br />
                        <em>{{ option.uom }} - {{ option.barcode }}</em>
                      </template>
                      <template slot="selected-option" slot-scope="option">{{
                        `${option.item_code +
                          ' ' +
                          option.extended_desc +
                          ' (' +
                          option.uom +
                          ') '}`
                      }}</template>
                    </v-select>
                  </div>
                </div>
              </div>
            </div>
          </form>
          <table class="table table-striped table-vcenter table-hover">
            <thead>
              <th class="text-main" style="width: 10%">
                Item Code
              </th>
              <th class="text-main" style="width: 10%">
                Barcode
              </th>
              <th class="text-main" style="width:45%">
                Description
              </th>
              <th class="text-main" style="width: auto">
                Uom
              </th>
              <th class="text-main" style="width: 15%">Qty</th>
              <th class="text-main" style="width: 15%" v-if="editItem != null">
                Nav Qty
              </th>
              <th class="text-main" style="width: auto">Action</th>
            </thead>
            <tbody>
              <tr v-if="!itemList.length">
                <td colspan="6" style="text-align: center;">
                  No data available.
                </td>
              </tr>
              <tr v-for="(data, index) in itemList" :key="index">
                <td class="text-main text-thin" style="width: 10%">
                  {{
                    data.item_code
                      ? data.item_code
                      : data.itemcode
                      ? data.itemcode
                      : '-'
                  }}
                </td>
                <td class="text-main text-thin" style="width: 10%">
                  {{ data.barcode ? data.barcode : '-' }}
                </td>
                <td class="text-main text-normal italic" style="width: 45%">
                  {{
                    data.extended_desc ? data.extended_desc : data.description
                  }}
                </td>
                <td class="text-main text-normal italic" style="width: auto">
                  {{ data.uom }}
                </td>
                <td class="text-main text-normal" style="width: 15%">
                  <input
                    class="form-control font-medium text-xl"
                    type="number"
                    min="1"
                    autocomplete="off"
                    placeholder="Input Qty"
                    v-model.number="qty[index]"
                    @keypress="isNumber($event)"
                    ref="handcarry"
                    v-if="editItem == null"
                  />
                  <span v-else>{{ data.qty }}</span>
                </td>
                <td
                  class="text-main text-normal"
                  style="width: 15%"
                  v-if="editItem != null"
                >
                  <input
                    class="form-control font-medium text-xl"
                    type="number"
                    min="1"
                    autocomplete="off"
                    placeholder="Input Qty"
                    v-model.number="navQty[index]"
                    @keypress="isNumber($event)"
                    ref="handcarry"
                  />
                </td>
                <td class="text-main text-normal" style="width: auto">
                  <button
                    class="btn btn-xs pull-right text-white font-medium bg-red-500 focus:outline-none border-red-500"
                    :disabled="editItem != null"
                    :class="{
                      'cursor-not-allowed opacity-50': editItem != null
                    }"
                    @click="removeBtn(index)"
                  >
                    <span aria-hidden="true">×</span>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="modal-footer">
          <button
            type="button"
            class="btn btn-secondary"
            data-dismiss="modal"
            aria-label="Close"
            @click="closeBtn()"
          >
            CLOSE
          </button>

          <button
            type="button"
            class="inline-flex justify-center rounded-md border border-transparent p-4 bg-red-600 text-lg leading-6 font-medium text-white shadow-sm hover:bg-red-500 focus:outline-none focus:border-red-700 focus:shadow-outline-red transition ease-in-out duration-150 focus:ring focus:ring-violet-300"
            @click="saveBtn"
            data-dismiss="modal"
            aria-label="Close"
            :disabled="invalid == false"
            :class="{
              'cursor-not-allowed opacity-50': invalid == false
            }"
          >
            SAVE
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Vue from 'vue'
import Form from 'vform'
import vSelect from 'vue-select'
import 'vue-select/dist/vue-select.css'
import { debounce } from 'lodash'
Vue.component('v-select', vSelect)
export default {
  props: [
    'company',
    'business_unit',
    'department',
    'section',
    'date',
    'showRackSetup',
    'editItem'
  ],
  data() {
    return {
      data: [],
      rack_name: null,
      rack: new Form({
        id: null,
        name: null,
        company: null,
        business_unit: null,
        department: null,
        section: null
      }),
      categoryList: [],
      itemList: [],
      forPrintItems: [],
      item: null,
      message: null,
      qty: [],
      navQty: []
    }
  },
  watch: {
    item(newValue) {
      if (this.showRackSetup == true) {
        if (newValue != null) {
          var exists = this.itemList.find(function(value) {
            return value.item_code == newValue.item_code
          })

          if (!exists) {
            this.itemList.push(newValue)
            this.qty.push(newValue)
            this.navQty.push(newValue)
          }

          this.item = null
        }
      }
    },
    showRackSetup() {
      if (this.showRackSetup == true) {
        // console.log(this.editItem)
        // this.$nextTick(() => this.$refs.rackname.focus())
      }
    },
    editItem() {
      if (this.editItem) this.itemList.push(this.editItem)
    }
  },
  computed: {
    invalid() {
      // console.log(this.date)
      if (this.qty.length != 0 && this.editItem == null) {
        let result = this.qty.every(function(e) {
          return e > 0
        })

        if (result) return result
      }
      if (this.navQty != 0 && this.editItem != null) {
        let result = this.navQty.every(function(e) {
          return e > 0
        })
        if (result) return result
      }
      return false
    }
  },
  methods: {
    saveBtn() {
      // console.log(this.date)
      Object.entries(this.itemList).forEach(([test, value], index) => {
        console.log(value, 1)
        value.qty = this.qty[test]
        value.navQty = this.navQty[test]
        console.log(value, 2)
      })

      const xdata = btoa(JSON.stringify(this.itemList))

      axios
        .get(
          `/setup/countBackendSetup/postCount/?date=${btoa(
            this.date
          )}&data=${xdata}&bu=${this.business_unit}&dept=${
            this.department
          }&section=${this.section}`
        )
        .then((response, status) => {
          if (response.status == 200) {
            this.$emit('saveSuccess', false)
            this.itemList = []
            this.forPrintItems = []
            this.item = null
            this.qty = []
            this.navQty = []
          } else {
            console.log('wtf')
          }
        })
    },
    removeBtn: function(index) {
      this.itemList.splice(index, 1)
      this.qty.splice(index, 1)
      this.navQty.splice(index, 1)
    },
    isNumber: function(evt) {
      evt = evt ? evt : window.event
      var charCode = evt.which ? evt.which : evt.keyCode
      // console.log(charCode)
      if (
        charCode > 31 &&
        (charCode < 48 || charCode > 57)
        // &&
        // charCode !== 46
      ) {
        evt.preventDefault()
      } else {
        return true
      }
    },
    closeBtn() {
      this.rack_name = null
      this.message = null
      this.rack.reset()
      this.$emit('closeMdl', false)
    },
    retrieveCategory(search, loading) {
      if (search) {
        loading(true)
        this.search2(search, loading, this)
      }
    },
    search2: debounce((search, loading, vm) => {
      if (search.trim().length > 0) {
        axios
          .get(`/setup/masterfiles/getItems?item=${search}`)
          .then(({ data }) => {
            vm.categoryList = data
            loading(false)
          })
          .catch(error => {
            vm.categoryList = []
            loading(false)
          })
      } else {
        vm.categoryList = []
        loading(false)
      }
    }, 1000),
    async getCategory() {
      return await axios.get('/uploading/nav_upload/getCategory')
    },
    getResults() {
      Promise.all([this.getCategory()]).then(response => {
        this.categoryList = response[0].data
        this.itemList = response[0].data
      })
    }
  },
  mounted() {
    console.log(this.editItem)
  }
}
</script>

<style scoped>
#container .table td {
  font-size: 1.1em;
}

#container .table > tbody > tr:hover {
  background-color: rgb(2 2 2 / 5%);
}

h5 {
  font-size: 15px;
}
</style>
