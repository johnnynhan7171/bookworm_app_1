import React from "react";
import "./style.scss"
function Footer(){
    const year = new Date().getFullYear()
    return (
        <div className="bookworm__footer" >
            <div className="bookworm__footer__text">
                <h1>BookWorm Store</h1>
                <h5>Address: 364 Cong Hoa Street, Tan Binh District, Ho Chi Minh City</h5>
                <h5>Phone: +84 28 3810 6200</h5>
                <p>Copyright {year} developed by Luong Huu Nhan. All rights reserved.</p>
            </div>
        </div>
    );
}
export default Footer;
