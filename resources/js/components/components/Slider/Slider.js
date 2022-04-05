import React, { Fragment } from 'react';

function Slider() {
    return (
        <Fragment>
            <div className="hero__item set-bg" data-setbg="/templateJs/assets/img/hero/banner.jpg">
                <div className="hero__text">
                    <span>AUCTIONS</span>
                    <h2>Nhanh nhất <br />Rẻ nhất</h2>
                    <p>Tham gia ngay</p>
                    <a href="#" className="primary-btn">Bán hàng</a>
                </div>
            </div>
        </Fragment>
    )
}

export default Slider;