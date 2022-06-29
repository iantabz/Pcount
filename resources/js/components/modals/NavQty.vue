<!-- @format -->
<template>
  <div>
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Nav Qty</h5>
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
          </form>
          <table class="table table-striped table-vcenter table-hover">
            <thead>
              <th class="text-main" style="width: auto">
                Item Code
              </th>
              <th class="text-main" style="width: auto">
                Barcode
              </th>
              <th class="text-main" style="width:45%">
                Extended Description
              </th>
              <th class="text-main" style="width: auto">
                Uom
              </th>
              <th class="text-main" style="width: 15%">Qty</th>
              <th class="text-main" style="width: 15%">Nav Qty</th>
              <th class="text-main" style="width: auto">Action</th>
            </thead>
            <tbody>
              <tr v-if="!itemList.length">
                <td colspan="6" style="text-align: center;">
                  No data available.
                </td>
              </tr>
              <tr v-for="(data, index) in itemList" :key="index">
                <td class="text-main text-thin" style="width: auto">
                  {{ data.item_code }}
                </td>
                <td class="text-main text-thin" style="width: auto">
                  {{ data.barcode }}
                </td>
                <td class="text-main text-normal italic" style="width: 45%">
                  {{ data.extended_desc }}
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
                  />
                </td>
                <td class="text-main text-normal" style="width: 15%">
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

export default {
  props: [],
  data() {
    return {}
  },
  methods: {},
  mounted() {}
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
