<!-- @format -->

<template>
  <div>
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5
            class="modal-title bord-btm text-thin"
            id="mdlTitle"
            style="padding: 5px 15px 15px 15px; font-size: 20px;"
          >
            <i class="demo-pli-map-2 icon-lg"></i> Rack setup
          </h5>
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
          <div class="table-responsive">
            <form class="form-horizontal" @submit.prevent>
              <div class="panel-body">
                <label
                  for="demo-oi-definput"
                  class="control-label text-semibold"
                  >Rack name</label
                >
                <div class="input-group">
                  <input
                    id="demo-oi-definput"
                    type="text"
                    placeholder="Input Rack name"
                    class="form-control text-xl"
                    style="font-style: 14px;"
                    v-model="rack_name"
                    ref="rackname"
                  />
                  <span class="input-group-btn">
                    <button
                      v-if="!rack.id"
                      class="btn btn-warning"
                      type="button"
                      @click="submitBtn()"
                    >
                      Add
                    </button>
                    <button
                      v-else
                      class="btn btn-warning"
                      type="button"
                      @click="submitBtn()"
                    >
                      Update
                    </button>
                  </span>
                </div>
                <small
                  class="help-block text-danger text-bold"
                  data-bv-for="demo-oi-definput"
                  style=""
                  v-if="message"
                  >{{ message }}</small
                >
              </div>
            </form>
            <table class="table table-striped table-vcenter table-hover">
              <thead>
                <th class="text-main text-center" style="width: 30%">
                  Rack name
                </th>
                <th class="text-main text-center" style="20%">Action</th>
              </thead>
              <tbody>
                <tr v-if="!data.length">
                  <td colspan="2" style="text-align: center;">
                    No data available.
                  </td>
                </tr>
                <tr v-for="(data, index) in data" :key="index">
                  <td class="text-main text-normal" style="width: 30%">
                    {{ data.rack_name }}
                  </td>
                  <td
                    class="text-main text-normal text-center"
                    style="width: 20%"
                  >
                    <button class="btn btn-info btn-xs" @click="editBtn(data)">
                      <i class="demo-pli-gear icon-lg icon-fw"></i>
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div class="modal-footer">
          <button
            type="button"
            class="btn btn-secondary"
            data-dismiss="modal"
            aria-label="Close"
            @click="closeBtn()"
          >
            Close
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Form from 'vform'

export default {
  props: ['company', 'business_unit', 'department', 'section', 'showRackSetup'],
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
      message: null
    }
  },
  watch: {
    showRackSetup() {
      if (this.showRackSetup == true) {
        this.getResults()
        this.$nextTick(() => this.$refs.rackname.focus())
      }
    }
  },
  methods: {
    editBtn(data) {
      this.rack_name = data.rack_name
      this.rack.id = data.id
      this.rack.name = data.rack_name
      this.rack.company = data.company
      this.rack.business_unit = data.business_unit
      this.rack.department = data.department
      this.rack.section = data.section
      this.$nextTick(() => this.$refs.rackname.focus())
    },
    submitBtn() {
      this.rack.name = this.rack_name
      this.rack.company = this.company
      this.rack.business_unit = this.business_unit
      this.rack.department = this.department
      this.rack.section = this.section

      this.rack
        .post('/setup/location/createRack')
        .then(({ data, status, text }) => {
          //   if ((status = 200)) {
          $.niftyNoty({
            type: 'success',
            icon: 'fa fa-check icon-2x',
            message: data.message,
            container: 'floating',
            timer: 5000
          })
          this.getResults()
          this.rack_name = null
          this.message = null
          this.rack.reset()
          //   }
        })
        .catch(({ response }) => {
          const { status, data } = response
          //   console.log(status, data)
          this.message = data.message
          this.$nextTick(() => this.$refs.rackname.focus())
          $.niftyNoty({
            type: 'danger',
            icon: 'fa fa-exclamation-triangle',
            message: data.message,
            container: 'floating',
            timer: 5000
          })
        })
    },
    getResults() {
      axios
        .get(
          `/setup/location/getRacks?company=${this.company}&business_unit=${this.business_unit}&department=${this.department}&section=${this.section}`
        )
        .then(response => {
          this.data = response.data
          console.log(response.data)
        })
    },
    closeBtn() {
      this.rack_name = null
      this.message = null
      this.rack.reset()
      this.$emit('closeMdl', false)
    }
  },
  mounted() {}
}
</script>

<style scoped>
#container .table td {
  font-size: 1.1em;
}

#container .table-hover > tbody > tr:hover {
  background-color: rgb(2 2 2 / 5%);
}
</style>
