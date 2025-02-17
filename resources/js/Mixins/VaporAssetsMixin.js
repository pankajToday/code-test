import Vapor from 'laravel-vapor';

export const VaporAssetsMixin = {
    data() {
        return {
            asset: Vapor.asset
        }
    }
};
