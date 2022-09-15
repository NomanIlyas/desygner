import axios from 'axios';

export const getImages = (params) => {
    return axios.get(`${process.env.REACT_APP_API_SERVER_PATH}/images`, {
            params
        }, {
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(res => {
            const {data} = res
            const imagesData = []
            if (data) {
                const images = data["hydra:member"]
                
                if (images && images.length) {
                    images.forEach(image => {
                        imagesData.push({
                            'id': image.id,
                            'name': image.name,
                            'source': image.source
                        })
                    });
                }
            }
            return {
                "images": imagesData,
                "totalCount": data ? data["hydra:totalItems"] : 0
            }
        })
}