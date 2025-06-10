
import { createContext, ReactNode, useState } from "react";

type Adsense = {
  id: number;
  title: string;
  description: string;
  price: number;
  userId: number;
  createdAt: string;
};

type AdsenseDTO = {
  title: string;
  description: string;
  price: number;
  userId: number;
};

type AdsenseContextType = {
  adsenseList: Adsense[];
  listAdsense: () => Adsense[];
  createAdsense: (data: AdsenseDTO) => void;
  deleteAdsense: (id: number) => void;
};

const AdsenseData: Adsense[] = [
  {
    id: 1,
    title: "sexo",
    description: "Muito bom, muita vontade de ver um jogo do mungao",
    price: 5000,
    userId: 5,
    createdAt: "15/07",
  },
  {
    id: 2,
    title: "métricas",
    description: "Resultado que vc ta obtendo",
    price: 152,
    userId: 9,
    createdAt: "09/07",
  },
];

const AdsenseContext = createContext<AdsenseContextType | undefined>(undefined);

export const AdsenseProvider = ({ children }: { children: ReactNode }) => {
  const [adsenseList, setAdsenseList] = useState<Adsense[]>(AdsenseData);

  const listAdsense = () => {
    return adsenseList;
  };

  const createAdsense = (data: AdsenseDTO) => {
    const { userId, ...rest } = data;
    const newAdsense = { ...data, id: 3, createdAt: "25/12" };
    setAdsenseList((prev) => [...prev, newAdsense]);
  };

  const deleteAdsense = (id: number) => {
    const removeById = (listAdsense: Adsense[], idToRemove: number) => {
      const isNotTargetId = (item: Adsense) => {
        return item.id !== idToRemove;
      };

      const filteredList = listAdsense.filter(isNotTargetId);
      return filteredList;
    };

    const novaLista = removeById(AdsenseData, id);
    return novaLista;
  };

  const value: AdsenseContextType = {
    adsenseList,
    listAdsense,
    createAdsense,
    deleteAdsense,
  };

  return (
    <AdsenseContext.Provider value={value}>
      {children}
    </AdsenseContext.Provider>
  );
};
