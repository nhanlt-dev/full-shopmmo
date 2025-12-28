<div class="page-title home01">
    <div class="tf-container ">
        <div class="row justify-center relative">
            <div class="col-lg-8 ">
                <div class="content-inner">
                    <div class="heading-title">
                        <h1 class="title">Tìm Kiếm Doanh Nghiệp</h1>
                        <p class="h6 fw-4">Hàng ngàn doanh nghiệp giống như bạn đang truy cập trang web của chúng tôi
                        </p>
                    </div>
                    <div class="wg-filter">
                        <div class="form-title">
                            <form onsubmit="return redirectToSearch();">
                                <div class="search-header d-flex jcsb">
                                    <fieldset class="form-input-suggestions">
                                        <input class="searchInputHeader" type="text" id="searchInput" placeholder="Nhập tên doanh nghiệp bạn cần tìm..." required autocomplete="off">
                                        <div class="suggestionInput" id="suggestions"></div>
                                    </fieldset>
                                    <div class="box-item wrap-btn">
                                        <button type="submit" class="tf-btn bg-color-primary pd-3">Tìm Ngay <i class="icon-MagnifyingGlass fw-6"></i></button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function redirectToSearch() {
        const query = document.getElementById('searchInput').value;
        if (query) {
            window.location.href = 'search/' + encodeURIComponent(query) + '/';
        }
        return false;
    }
</script>
<script>
    $(document).ready(function () {
      $.ajax({
        url: "src/apis/getTitleProduct.php",
        method: "GET",
        dataType: "json",
        success: function (response) {
          const products = response;
    
          document
            .getElementById("searchInput")
            .addEventListener("input", function () {
              const query = this.value.toLowerCase();
              const suggestions = document.getElementById("suggestions");
              suggestions.innerHTML = "";
    
              if (query) {
                const filteredProducts = products.filter((product) => {
                  const normalizedProduct = product.pageName
                    .toLowerCase()
                    .normalize("NFD")
                    .replace(/[\u0300-\u036f]/g, "");
                  const normalizedQuery = query
                    .normalize("NFD")
                    .replace(/[\u0300-\u036f]/g, "");
                  return normalizedProduct.includes(normalizedQuery);
                });
    
                const limitedSuggestions = filteredProducts.slice(0, 8);
                limitedSuggestions.forEach((product) => {
                  const div = document.createElement("div");
                  div.className = "suggestion-item";
                  div.textContent = product.pageName;
                  div.onclick = () => {
                    window.location.href = product.pageUrl + "/";
                  };
                  suggestions.appendChild(div);
                });
    
                suggestions.style.display = limitedSuggestions.length
                  ? "block"
                  : "none";
              } else {
                suggestions.style.display = "none";
              }
            });
        },
        error: function (xhr, status, error) {
          console.error("Error fetching title product:", status, error);
        },
      });
    });
</script>