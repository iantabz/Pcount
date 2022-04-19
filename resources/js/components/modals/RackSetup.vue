<!-- @format -->

<template>
  <div>
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="mdlTitle">Rack setup</h5>
          <button
            type="button"
            class="close"
            data-dismiss="modal"
            aria-label="Close"
          >
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="table-responsive">
            <form class="form-horizontal">
              <div class="panel-body">
                <label
                  for="demo-oi-definput"
                  class="control-label text-semibold"
                  >Rack name</label
                >
                <div class="input-group mar-btm">
                  <input
                    id="demo-oi-definput"
                    type="text"
                    placeholder="Input Rack name"
                    class="form-control"
                    style="font-style: 12px;"
                  />
                  <span class="input-group-btn">
                    <button class="btn btn-warning" type="button">
                      Add
                    </button>
                  </span>
                </div>
              </div>
            </form>
            <table class="table table-striped table-vcenter table-hover">
              <thead>
                <th class="text-main text-center">Rack name</th>
                <th class="text-main text-center">Action</th>
              </thead>
              <tbody>
                <tr v-if="!data.data.length">
                  <td colspan="2" style="text-align: center;">
                    No data available.
                  </td>
                </tr>
                <tr v-for="(data, index) in data.data" :key="index">
                  <td class="text-main text-normal"></td>
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
          >
            Close
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      data: {
        data: [],
        from: null,
        to: null,
        total: null,
        per_page: null,
        current_page: null
      }
    }
  },
  methods: {
    getResults() {
      console.log('getting data')
    }
  },
  mounted() {
    this.getResults()
  }
}
</script>

<style lang="scss" scoped></style>
