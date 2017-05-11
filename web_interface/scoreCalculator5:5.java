package mains;
import java.io.File;
import java.io.IOException;
import java.sql.*;
import java.util.ArrayList;
import java.util.Arrays;

import javax.servlet.annotation.WebServlet;

import org.apache.lucene.analysis.Analyzer;
import org.apache.lucene.index.DirectoryReader;
import org.apache.lucene.index.IndexReader;
import org.apache.lucene.queryparser.classic.ParseException;
import org.apache.lucene.search.IndexSearcher;
import org.apache.lucene.store.FSDirectory;
import org.apache.tomcat.jni.Time;

/**
 * Added by sak2km 4/27/17
 */
public class ScoreCalculator {
	final int WOS_number;
	int weight;
	int score;
    double[][] feature;
	
	public ScoreCalculator(int WOS_no) {
		WOS_number = WOS_no;		

    }
	
	static double calculateScore(double[] feature) throws ClassNotFoundException{
		double score = 0;
		double[]weight = loadWeight(20);
	//	double [] weight = new double[20];		// hard-coded for now. Should be replaced by loaded value from DB
		for (int i=0;i<weight.length;i++){
//			weight[i]=1;					// to be removed once weight is loaded
			score += weight[i]*feature[i];
		}
		return score;
	}
	
	double updateWeight(double[] weight, double[][] documentSet){
		
		return 0;
	}
	
	static double[] loadWeight(int numofFeature) throws ClassNotFoundException{
		double[] weight = new double[numofFeature];
		Class.forName("com.mysql.jdbc.Driver");
		try {
			Connection myConn = DriverManager.getConnection("jdbc:mysql://hcdm.cs.virginia.edu","logger","lOGGing");			
			Statement myStmt = myConn.createStatement();
			String sqlQuery = "SELECT * FROM LiteratureSearchEngine.weight_vectors where date=(SELECT max(date) FROM LiteratureSearchEngine.weight_vectors);";			
			ResultSet myRS = myStmt.executeQuery(sqlQuery);		
			while(myRS.next()){
				System.out.println("Weight:");
				for(int i=0;i<weight.length;i++){
					String weightVal = myRS.getString(Integer.toString(i));
					if(weightVal!=null)
						weight[i]=Double.parseDouble(weightVal);
					else weight[i] = 1;
					System.out.print(weight[i]+" ");
				}
				System.out.println("-----------------------------------------------------------");
			}				
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}	
		return weight;
	}
	
	public static void saveWeight(double[] weight) throws ClassNotFoundException{
		String saveVal="";
		Class.forName("com.mysql.jdbc.Driver");
		for(int i=0;i<weight.length;i++){
				saveVal = saveVal+", "+weight[i];
		}
		
		try {
			Connection myConn = DriverManager.getConnection("jdbc:mysql://hcdm.cs.virginia.edu","logger","lOGGing");			
			Statement myStmt = myConn.createStatement();
			String sqlQuery = "INSERT INTO LiteratureSearchEngine.weight_vectors values(null,NOW()"+saveVal+",null);";//Last 'null'is for MAP score
			int success = myStmt.executeUpdate(sqlQuery);		
			if(success==1){
				System.out.println("Updated weight successfully");				
			}	
			
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
	}
	
	public static String [][] generatePairs(String[] WOS_list, String[] clicked_list){	//takes whole list of displayed doc. WOS array & array of clicked times	
		ArrayList<Integer> clickedPosition = new ArrayList<Integer>();
	//	ArrayList<String> displayed_list = new ArrayList<String>(Arrays.asList(WOS_list));
		int numPairs=0;
		for(int i=0;i<WOS_list.length;i++){	//locate the positions of clicked document
			for(int j=0;j<clicked_list.length;j++){
				if(WOS_list[i]!=null && clicked_list[j]!=null){
					if(WOS_list[i].equals(clicked_list[j]) && !clickedPosition.contains(i)){
						clickedPosition.add(i);
						numPairs+=i-clickedPosition.size()+1;	
					}					
				}
			}
		}
		String [][] rankedPair = new String[numPairs][2];
		int counter=0;
		for(int j=0;j<clickedPosition.size();j++){
			for(int i=0;i<clickedPosition.get(j);i++){	//fill in pairwise rank info array
				if(!clickedPosition.contains(i)){
					rankedPair[counter][0] = WOS_list[clickedPosition.get(j)]; //high
					rankedPair[counter][1] = WOS_list[i];	//low	
					counter++;
				}
			}
		}			
		return rankedPair;
	}
	
	public static String getSessionId(String userId) throws ClassNotFoundException{
		int maxSessionId = 0;
		String [] session=new String[2];
		Class.forName("com.mysql.jdbc.Driver");
/*		 Looks up both displayed/clicked log tables and returns new session ID if new user or latest session expired
		 if latest session is still alive, return the session ID.
*/		
		try {	
			Connection myConn = DriverManager.getConnection("jdbc:mysql://hcdm.cs.virginia.edu","logger","lOGGing");			
			Statement myStmt = myConn.createStatement();			
			// Look up seach_logs table for latest session within 30 min.
			String sqlQuery = "SELECT * FROM LiteratureSearchEngine.search_logs WHERE time >= now() - INTERVAL 30 minute AND time=(SELECT max(time) FROM LiteratureSearchEngine.search_logs) AND user_id='"+userId+"';";			
			ResultSet myRS = myStmt.executeQuery(sqlQuery);		
			if(myRS.next()){
				session[0]=myRS.getString("session_id");
				session[1]=myRS.getString("time");				
			}
			else{	// if no alive session, get the max session number for new session.
				sqlQuery = "SELECT * FROM LiteratureSearchEngine.search_logs WHERE session_id=(SELECT max(session_id) FROM LiteratureSearchEngine.search_logs);";			
				myRS = myStmt.executeQuery(sqlQuery);
				while(myRS.next()){
					maxSessionId = Integer.parseInt(myRS.getString("session_id"));		
				}
			}
			
			// Look up click_logs table for latest session within 30 min.
			sqlQuery = "SELECT * FROM LiteratureSearchEngine.click_logs WHERE time >= now() - INTERVAL 30 minute AND time=(SELECT max(time) FROM LiteratureSearchEngine.click_logs) AND user_id='"+userId+"';";			
			myRS = myStmt.executeQuery(sqlQuery);		
			if(myRS.next()){
				if(session[0]!=null &&	// if session from click_logs table is more recent, use it
				Timestamp.valueOf(session[1]).compareTo(Timestamp.valueOf(myRS.getString("time")))<0){
					session[0]=myRS.getString("session_id");
					session[1]=myRS.getString("time");	
				}				
				else{
					session[0]=myRS.getString("session_id");
					session[1]=myRS.getString("time");		
				}			
			}
			else{	// if no alive session, get the max session number for new session.
				sqlQuery = "SELECT * FROM LiteratureSearchEngine.click_logs WHERE session_id=(SELECT max(session_id) FROM LiteratureSearchEngine.click_logs);";			
				myRS = myStmt.executeQuery(sqlQuery);
				while(myRS.next()){
					if(Integer.parseInt(myRS.getString("session_id"))>maxSessionId)	//Compare the largest session # from the two tables
						maxSessionId = Integer.parseInt(myRS.getString("session_id"));		
				}
			}						
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		if(session[0]!=null){	//If there was at least 1 alive session, return most recent
			return session[0];
		}
		else{
			return Integer.toString(maxSessionId+1);	// else return new session ID
		}
	}
	
	public static ArrayList<ArrayList<String>> findMatch(String userId, String searchQuery, String sessionId) throws ClassNotFoundException{
		Class.forName("com.mysql.jdbc.Driver");
		ArrayList<String> searched = new ArrayList<String>();
		ArrayList<String> clicked = new ArrayList<String>();
		
		
		try {
			Connection myConn = DriverManager.getConnection("jdbc:mysql://hcdm.cs.virginia.edu","logger","lOGGing");			
			Statement myStmt = myConn.createStatement();
			// Search search_logs table
			String timeQuery = "AND time=(SELECT max(time) FROM LiteratureSearchEngine.search_logs WHERE user_Id='"+ userId+"' AND session_Id='"+sessionId+"' AND query='"+searchQuery+"');";
			String sqlQuery = "SELECT * FROM LiteratureSearchEngine.search_logs WHERE user_Id='"+userId+"' AND session_Id='"+sessionId+"' AND query='"+searchQuery+"'"+ timeQuery;			
			ResultSet myRS = myStmt.executeQuery(sqlQuery);		
			while(myRS.next()){
				searched = new ArrayList<String>(Arrays.asList(myRS.getString("lucene_index"/*"wos_list"*/).split(",")));
			}
			
			// Search click_logs table
			sqlQuery = "SELECT * FROM LiteratureSearchEngine.click_logs WHERE user_Id='"+userId+"' AND session_Id='"+sessionId+"' AND query='"+searchQuery+"';";			
			myRS = myStmt.executeQuery(sqlQuery);		
			while(myRS.next()){
				clicked.add(myRS.getString("lucene_index"/*"wos"*/));
			}
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		
		//Define ArrayList to return. First element for "searched" log, second for "clicked".
		ArrayList<ArrayList<String>> resultArray = new ArrayList<ArrayList<String>>();
		resultArray.add(searched);
		resultArray.add(clicked);

		
		return resultArray;		//result must be turned into 2arrays to generate rank
	}
	


	public static void main(String[] args) throws ClassNotFoundException, IOException, ParseException {
//		String sessionId = getSessionId("137.54.17.26");
//		System.out.println(sessionId);
		String query = "information retrieval";
		ArrayList<ArrayList<String>> resultArray = findMatch("::1",query,"12");
		
		String[] WOS_list, clicked_list;
		WOS_list = resultArray.get(0).toArray(new String[resultArray.get(0).size()]);
		clicked_list = resultArray.get(1).toArray(new String[resultArray.get(1).size()]);
		
		String [][] rankedPair = ScoreCalculator.generatePairs(WOS_list,clicked_list);
		
		/*Create [][]Integer for now*/
		Integer[][] rankedPair_int = new Integer[rankedPair.length][2];
		for(int i=0;i<rankedPair.length;i++){
			for(int j=0;j<2;j++){
				rankedPair_int[i][j]= Integer.parseInt(rankedPair[i][j]);
			}
			
		}		
		
		/*Print Document pairs for testing*/
		String toString="";
		if(rankedPair.length>0) System.out.println("High	Low");
		for(int i=0;i<rankedPair.length;i++){
			toString="";
			for(int j=0;j<2;j++){
				toString=toString+rankedPair[i][j].toString()+"  ";
			}
			System.out.println(toString);
		}
		
		
		/*Define L2RWeight class to generate features and weight*/
		String _indexPath = System.getenv().get("Index_path");
		IndexReader reader = DirectoryReader.open(FSDirectory.open(new File(_indexPath)));
        L2RWeight l2RWeight = new L2RWeight(reader);
        l2RWeight.ExtractFeature(query);
        l2RWeight.ConstructList();
        
        
        System.out.println("Weight before update:");
        for(int i=0;i<l2RWeight.weight.length;i++){
			System.out.print(l2RWeight.weight[i]+" ");
		}

		System.out.println();
        System.out.println("Weight after update:");
        l2RWeight.update(rankedPair_int);
        for(int i=0;i<l2RWeight.weight.length;i++){
			System.out.print(l2RWeight.weight[i]+" ");
		}

        l2RWeight.ConstructList();
		
		saveWeight(l2RWeight.weight);
	}

}
