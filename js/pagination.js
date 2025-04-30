function getURL(pageNumber = 1) {
    const searchParam = new URLSearchParams(location.search);
    if (pageNumber > 1) searchParam.set("page", pageNumber);
    else searchParam.delete("page");
  
    return location.origin + location.pathname + "?" + searchParam.toString();
  }
  
  function pagination(page, total) {
    if (total < 2) return "";
    
    if (total < 4) return `
      <a href="${getURL()}" class="bg-pink-50 text-red-700 hover:bg-pink-800 hover:text-white duration-150 px-2 py-1 ${page === 1 && "current"}">1</a>
      <a href="${getURL(2)}" class="bg-pink-50 text-red-700 hover:bg-pink-800 hover:text-white duration-150 px-2 py-1 ${page === 2 && "current"}">2</a>
    ` + (total === 3 ? `<a href="${getURL(3)}" class="bg-pink-50 text-red-700 hover:bg-pink-800 hover:text-white duration-150 px-2 py-1 ${page === 3 && "current"}">3</a>` : '');
  
    if (page === 1) return `
      <button class="bg-pink-50 text-red-700 hover:bg-pink-800 hover:text-white duration-150 px-2 py-1 current">1</button>
      <a href="${getURL(2)}" class="bg-pink-50 text-red-700 hover:bg-pink-800 hover:text-white duration-150 px-2 py-1">2</button>
      <a href="${getURL(3)}" class="bg-pink-50 text-red-700 hover:bg-pink-800 hover:text-white duration-150 px-2 py-1">3</a>
      <a href="${getURL(total)}" class="bg-pink-50 text-red-700 hover:bg-pink-800 hover:text-white duration-150 px-2 py-1">&gt;</a>
    `;
  
    else if (page < total) return `
      <a href="${getURL(1)}" class="bg-pink-50 text-red-700 hover:bg-pink-800 hover:text-white duration-150 px-2 py-1">&lt;</a>
      <a href="${getURL(page - 1)}" class="bg-pink-50 text-red-700 hover:bg-pink-800 hover:text-white duration-150 px-2 py-1">${page - 1}</a>
      <button class="bg-pink-50 text-red-700 hover:bg-pink-800 hover:text-white duration-150 px-2 py-1 current">${page}</button>
      <a href="${getURL(page + 1)}" class="bg-pink-50 text-red-700 hover:bg-pink-800 hover:text-white duration-150 px-2 py-1">${page + 1}</a>
      <a href="${getURL(total)}" class="bg-pink-50 text-red-700 hover:bg-pink-800 hover:text-white duration-150 px-2 py-1">&gt;</a>
    `;
  
    else return `
      <a href="${getURL(1)}" class="bg-pink-50 text-red-700 hover:bg-pink-800 hover:text-white duration-150 px-2 py-1">&lt;</a>
      <a href="${getURL(total - 2)}" class="bg-pink-50 text-red-700 hover:bg-pink-800 hover:text-white duration-150 px-2 py-1">${total - 2}</a>
      <a href="${getURL(total - 1)}" class="bg-pink-50 text-red-700 hover:bg-pink-800 hover:text-white duration-150 px-2 py-1">${total - 1}</a>
      <button class="bg-pink-50 text-red-700 hover:bg-pink-800 hover:text-white duration-150 px-2 py-1 current">${total}</button>
    `;
  }